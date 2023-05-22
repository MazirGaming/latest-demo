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

 /*
Name: Essence
Slug: essence
URI: https://vvveb.com/wp/template/essence/
Author: Vvveb
Author URI: https://vvveb.com
Description: Make an end to your hunt for the best free clean eCommerce website template with our fantastic tool, Essence. With Essence, you have it all in the apple pie order for the shopping experience like none out there. The template is responsive, retina ready and powered by Bootstrap 4 for the flexibility and stability that you need. Creating a top-notch online shop with Essence is something you can do right away.
Version: 2.0
License:  CC BY 3.0
License URI: https://vvveb.com/licence/
Tags: one-column, two-columns, right-sidebar, accessibility-ready, custom-background, custom-colors, custom-header, custom-menu, editor-style, featured-images, flexible-header, microformats, post-formats, rtl-language-support, sticky-post, threaded-comments, translation-ready, blog
Text Domain: essence
*/
use function Vvveb\__;

return
	[
		'pages' => [
			['title' =>  __('Homepage'), 'name' =>  'homepage', 'file'=> 'index.html', 'url'=> Vvveb\url('index/index')],
			//content
			['title' =>  __('Home'), 'name' =>  'blog', 'file'=> 'content/index.html', 'folder' => 'blog', 'url'=> Vvveb\url('content/index/index')],
			['title' => __('Post'), 'name' =>  'post', 'file'=> 'content/post.html', 'folder' => 'blog', 'url'=> Vvveb\url('content/post/index')],
			['title' => __('Category'), 'name' =>  'category', 'file'=> 'category.html', 'folder' => 'blog', 'url'=> Vvveb\url('content/category')],
			//ecommerce
			['title' =>  __('Product'), 'name' =>  'product', 'file'=> 'product.html', 'folder' => 'ecommerce', 'url'=> Vvveb\url('product/product/index')],
			['title' => __('Category'), 'name' =>  'category', 'file'=> 'category.html', 'folder' => 'ecommerce', 'url'=> Vvveb\url('product/category/index')],
			['title' => __('Manufacturer'), 'name' =>  'manufacturer', 'file'=> 'manufacturer.html', 'folder' => 'ecommerce', 'url'=> Vvveb\url('product/manufacturer/index')],
			['title' => __('Cart'), 'name' =>  'cart', 'file'=> 'cart.html', 'folder' => 'ecommerce', 'url'=> Vvveb\url('checkout/cart/index')],
			['title' => __('Checkout'), 'name' =>  'checkout', 'file'=> 'checkout.html', 'folder' => 'ecommerce', 'url'=> Vvveb\url('checkout/checkout/index')],
			//account
			['title' =>  __('Login'), 'name' =>  'login', 'file'=> 'account/login.html', 'folder' => 'account', 'url'=> Vvveb\url('account/login')],
			['title' => __('Dashboard'), 'name' =>  'dashboard', 'file'=> 'account/dashboard.html', 'folder' => 'account', 'url'=> Vvveb\url('account/dashboard')],
			['title' => __('Dashboard'), 'name' =>  'checkout', 'file'=> 'checkout.html', 'folder' => 'account', 'url'=> Vvveb\url('account/dashboard')],
			//mail
			['title' =>  __('Account confirm'), 'name' =>  'account_confirm', 'folder' => 'mail', 'file'=> 'mail/account_confirm.html', 'url'=> Vvveb\url('content/page/index', ['slug' => 'contact'])],
			['title' => __('Order confirm'), 'name' =>  'order_confirm', 'folder' => 'mail', 'file'=> 'mail/order_confirm.html', 'url'=> Vvveb\url('static/index', ['page' => 'contact'])],
			//static
			['title' =>  __('Contact'), 'name' =>  'error404', 'file'=> 'page.html', 'folder' => 'static', 'url'=> Vvveb\url('content/page/index', ['slug' => 'contact'])],
			['title' => __('Terms of use'), 'name' =>  'error404', 'file'=> 'error404.html', 'folder' => 'static', 'url'=> Vvveb\url('index/index')],
			['title' => __('Privacy policy'), 'name' =>  'error404', 'file'=> 'error404.html', 'folder' => 'static', 'url'=> Vvveb\url('index/index')],
			['title' => __('Payment options'), 'name' =>  'error404', 'file'=> 'error404.html', 'folder' => 'static', 'url'=> Vvveb\url('index/index')],
			['title' => __('Shipping and delivery'), 'name' =>  'error404', 'file'=> 'error404.html', 'folder' => 'static', 'url'=> Vvveb\url('index/index')],
			['title' => __('Page Not found (404)'), 'name' =>  'error404', 'file'=> 'error404.html', 'folder' => 'static', 'url'=> Vvveb\url('index/index')],
		],
		'components' => [
			['title' =>  __('Content'), 'name' =>  'content'],
			['title' => __('Ecommerce'), 'name' =>  'ecommerce'],
			['title' => __('Bootstrap 4'), 'name' =>  'bootstrap4'],
			['title' => __('Essence components'), 'name' =>  'essence', 'file'=> 'essence-components.js'],
		],
		'inputs' => [
			['title' =>  __('Slider inputs'), 'name' =>  'slider', 'file'=> 'slider-inputs.js'],
		],
		'ignoreFolders' => ['backup'],
	];
