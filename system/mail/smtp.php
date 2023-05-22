<?php

/**
 * Vvveb
 *
 * Copyright (C) 2022  Ziadin Givan
 *
 * This program is free software: you can redistribute it and/or modify
 * it under the terms of the GNU Affero General Public License as
 * published by the Free Software Foundation, either version 3 of the
 * License, or (at your option) any later version.
 *
 * This program is distributed in the hope that it will be useful,
 * but WITHOUT ANY WARRANTY; without even the implied warranty of
 * MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
 * GNU Affero General Public License for more details.
 *
 * You should have received a copy of the GNU Affero General Public License
 * along with this program.  If not, see <https://www.gnu.org/licenses/>.
 *
 */

namespace Vvveb\System\Mail;

class Smtp {
	protected array $option = [
		'smtp_port'     => 25,
		'smtp_timeout'  => 5,
		'max_attempts'  => 3,
		'verp'          => false,
	];

	public function __construct(array &$option = []) {
		$this->option += $option;

		if (! defined('EOL')) {
			define('EOL', "\r\n");
		}
	}

	public function attachments() {
	}

	public function send() {
		foreach (['smtp_hostname', 'smtp_username', 'smtp_password', 'smtp_port', 'smtp_timeout'] as $key) {
			if (! isset($this->option[$key])) {
				throw new \Exception("Error: $key required!");
			}
		}

		if (is_array($this->option['to'])) {
			$to = implode(',', $this->option['to']);
		} else {
			$to = $this->option['to'];
		}

		$boundary = '----=_NextPart_' . md5(time());

		$header = 'MIME-Version: 1.0' . EOL;
		$header .= 'To: <' . $to . '>' . EOL;
		$header .= 'Subject: =?UTF-8?B?' . base64_encode($this->option['subject']) . '?=' . EOL;
		$header .= 'Date: ' . date('D, d M Y H:i:s O') . EOL;
		$header .= 'From: =?UTF-8?B?' . base64_encode($this->option['sender']) . '?= <' . $this->option['from'] . '>' . EOL;

		if (empty($this->option['reply_to'])) {
			$header .= 'Reply-To: =?UTF-8?B?' . base64_encode($this->option['sender']) . '?= <' . $this->option['from'] . '>' . EOL;
		} else {
			$header .= 'Reply-To: =?UTF-8?B?' . base64_encode($this->option['reply_to']) . '?= <' . $this->option['reply_to'] . '>' . EOL;
		}

		$header .= 'Return-Path: ' . $this->option['from'] . EOL;
		$header .= 'X-Mailer: PHP/' . phpversion() . EOL;
		$header .= 'Content-Type: multipart/mixed; boundary="' . $boundary . '"' . EOL . EOL;

		$message = '--' . $boundary . EOL;

		if (empty($this->option['html'])) {
			$message .= 'Content-Type: text/plain; charset="utf-8"' . EOL;
			$message .= 'Content-Transfer-Encoding: base64' . EOL . EOL;
			$message .= base64_encode($this->option['text']) . EOL;
		} else {
			$message .= 'Content-Type: multipart/alternative; boundary="' . $boundary . '_alt"' . EOL . EOL;
			$message .= '--' . $boundary . '_alt' . EOL;
			$message .= 'Content-Type: text/plain; charset="utf-8"' . EOL;
			$message .= 'Content-Transfer-Encoding: base64' . EOL . EOL;

			if ($this->option['text']) {
				$message .= base64_encode($this->option['text']) . EOL;
			} else {
				$message .= base64_encode(strip_tags($this->option['html']), '<a>') . EOL;
			}

			$message .= '--' . $boundary . '_alt' . EOL;
			$message .= 'Content-Type: text/html; charset="utf-8"' . EOL;
			$message .= 'Content-Transfer-Encoding: base64' . EOL . EOL;
			$message .= base64_encode($this->option['html']) . EOL;
			$message .= '--' . $boundary . '_alt--' . EOL;
		}

		if (! empty($this->option['attachments'])) {
			foreach ($this->option['attachments'] as $attachment) {
				if (is_file($attachment)) {
					$handle = fopen($attachment, 'r');

					$content = fread($handle, filesize($attachment));

					fclose($handle);

					$message .= '--' . $boundary . EOL;
					$message .= 'Content-Type: application/octet-stream; name="' . basename($attachment) . '"' . EOL;
					$message .= 'Content-Transfer-Encoding: base64' . EOL;
					$message .= 'Content-Disposition: attachment; filename="' . basename($attachment) . '"' . EOL;
					$message .= 'Content-ID: <' . urlencode(basename($attachment)) . '>' . EOL;
					$message .= 'X-Attachment-Id: ' . urlencode(basename($attachment)) . EOL . EOL;
					$message .= chunk_split(base64_encode($content));
				}
			}
		}

		$message .= '--' . $boundary . '--' . EOL;

		$handle = fsockopen($this->option['smtp_hostname'], $this->option['smtp_port'], $errno, $errstr, $this->option['smtp_timeout']);

		if ($handle) {
			if (substr(PHP_OS, 0, 3) != 'WIN') {
				socket_set_timeout($handle, $this->option['smtp_timeout'], 0);
			}

			while ($line = fgets($handle, 515)) {
				if (substr($line, 3, 1) == ' ') {
					break;
				}
			}

			fputs($handle, 'EHLO ' . getenv('SERVER_NAME') . EOL);

			$reply = '';

			while ($line = fgets($handle, 515)) {
				$reply .= $line;

				//some SMTP servers respond with 220 code before responding with 250. hence, we need to ignore 220 response string
				if (substr($reply, 0, 3) == 220 && substr($line, 3, 1) == ' ') {
					$reply = '';

					continue;
				} elseif (substr($line, 3, 1) == ' ') {
					break;
				}
			}

			if (substr($reply, 0, 3) != 250) {
				throw new \Exception('Error: EHLO not accepted from server!');
			}

			if (substr($this->option['smtp_hostname'], 0, 3) == 'tls') {
				fputs($handle, 'STARTTLS' . EOL);

				$this->handleReply($handle, 220, 'Error: STARTTLS not accepted from server!');

				stream_socket_enable_crypto($handle, true, STREAM_CRYPTO_METHOD_TLS_CLIENT);
			}

			if (! empty($this->option['smtp_username']) && ! empty($this->option['smtp_password'])) {
				fputs($handle, 'EHLO ' . getenv('SERVER_NAME') . EOL);

				$this->handleReply($handle, 250, 'Error: EHLO not accepted from server!');

				fputs($handle, 'AUTH LOGIN' . EOL);

				$this->handleReply($handle, 334, 'Error: AUTH LOGIN not accepted from server!');

				fputs($handle, base64_encode($this->option['smtp_username']) . EOL);

				$this->handleReply($handle, 334, 'Error: Username not accepted from server!');

				fputs($handle, base64_encode($this->option['smtp_password']) . EOL);

				$this->handleReply($handle, 235, 'Error: Password not accepted from server!');
			} else {
				fputs($handle, 'HELO ' . getenv('SERVER_NAME') . EOL);

				$this->handleReply($handle, 250, 'Error: HELO not accepted from server!');
			}

			if ($this->option['verp']) {
				fputs($handle, 'MAIL FROM: <' . $this->option['from'] . '>XVERP' . EOL);
			} else {
				fputs($handle, 'MAIL FROM: <' . $this->option['from'] . '>' . EOL);
			}

			$this->handleReply($handle, 250, 'Error: MAIL FROM not accepted from server!');

			if (! is_array($this->option['to'])) {
				fputs($handle, 'RCPT TO: <' . $this->option['to'] . '>' . EOL);

				$reply = $this->handleReply($handle, false, 'RCPT TO [!array]');

				if ((substr($reply, 0, 3) != 250) && (substr($reply, 0, 3) != 251)) {
					throw new \Exception('Error: RCPT TO not accepted from server!');
				}
			} else {
				foreach ($this->option['to'] as $recipient) {
					fputs($handle, 'RCPT TO: <' . $recipient . '>' . EOL);

					$reply = $this->handleReply($handle, false, 'RCPT TO [array]');

					if ((substr($reply, 0, 3) != 250) && (substr($reply, 0, 3) != 251)) {
						throw new \Exception('Error: RCPT TO not accepted from server!');
					}
				}
			}

			fputs($handle, 'DATA' . EOL);

			$this->handleReply($handle, 354, 'Error: DATA not accepted from server!');

			// According to rfc 821 we should not send more than 1000 including the CRLF
			$message = str_replace(EOL, "\n", $header . $message);
			$message = str_replace("\r", "\n", $message);

			$lines = explode("\n", $message);

			foreach ($lines as $line) {
				// see https://php.watch/versions/8.2/str_split-empty-string-empty-array
				$results = ($line === '') ? [''] : str_split($line, 998);

				foreach ($results as $result) {
					fputs($handle, $result . EOL);
				}
			}

			fputs($handle, '.' . EOL);
			$this->handleReply($handle, 250, 'Error: DATA not accepted from server!');
			fputs($handle, 'QUIT' . EOL);
			$this->handleReply($handle, 221, 'Error: QUIT not accepted from server!');
			fclose($handle);

			return true;
		} else {
			throw new \Exception('Error: ' . $errstr . ' (' . $errno . ')');

			return false;
		}
	}

	private function handleReply($handle, $status_code = false, $error_text = false, $counter = 0) {
		$reply = '';

		while (($line = fgets($handle, 515)) !== false) {
			$reply .= $line;

			if (substr($line, 3, 1) == ' ') {
				break;
			}
		}

		// Wait for response
		if (! $line && empty($reply) && $counter < $this->option['max_attempts']) {
			sleep(1);

			$counter++;

			return $this->handleReply($handle, $status_code, $error_text, $counter);
		}

		if ($status_code) {
			if (substr($reply, 0, 3) != $status_code) {
				throw new \Exception($error_text);
			}
		}

		return $reply;
	}
}
