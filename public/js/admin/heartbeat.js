/*
 * Keep session open to avoid seeing login screen if not active for a few minutes, if session still expires login modal is shown.
*/

let HeartBeat = setInterval(function () {
	$.ajax(window.location.pathname + '?action=heartbeat')
		.done(function (sessionStatus) {
			console.log(sessionStatus);
			if (sessionStatus != "ok") {
				if ($("#login").length == 0) {
					$.ajax(window.location.pathname + '?module=user/login&modal=true').done(function (formHtml) {
						$("#heartBeatLogin .modal-body").html(formHtml);
						const heartBeatLogin = bootstrap.Modal.getOrCreateInstance('#heartBeatLogin');
						heartBeatLogin.show();
					});
				}
			}
		}).fail(function (data) {
			//alert(data.responseText);
		});;
}, 3 * 60 * 1000);//3 minutes

export {HeartBeat};
