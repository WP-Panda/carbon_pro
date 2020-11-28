<?php
/**
 * @package brabus.coms
 * @author  WP_Panda
 * @version 1.0.0
 */

defined( 'ABSPATH' ) || exit;
?>
<style>
	@-ms-keyframes wpp-preloader-spin {
		from { -ms-transform: rotate(0deg); }
		to { -ms-transform: rotate(360deg); }
	}
	@-moz-keyframes wpp-preloader-spin {
		from { -moz-transform: rotate(0deg); }
		to { -moz-transform: rotate(360deg); }
	}
	@-webkit-keyframes wpp-preloader-spin {
		from { -webkit-transform: rotate(0deg); }
		to { -webkit-transform: rotate(360deg); }
	}
	@keyframes wpp-preloader-spin {
		from {
			transform:rotate(0deg);
		}
		to {
			transform:rotate(360deg);
		}
	}

	@keyframes wpp-preloader-strikes {
		from {
			left: 25px;
		}
		to {
			left: -80px;
			opacity: 0;
		}
	}
	@keyframes wpp-preloader-dots {
		from {
			width: 0px;
		}
		to {
			width: 15px;
		}
	}
	@keyframes wpp-preloader-fadeIn {
		from {
			opacity: 0;
		}
		to {
			opacity: 1;
		}
	}

	.wpp-preloader-fadeIn, .wpp-preloader-loading-window {
		animation: wpp-preloader-fadeIn 0.4s both;
	}

	.wpp-preloader-wrap {
		background: rgb(51 51 51 / 50%);
		color: #ffffff;
		height: 100%;
		left: 0;
		top: 0;
		width: 100%;
		z-index: 99;
		position: fixed;
		margin: 0;
		padding: 0;
	}
	.wpp-preloader-text {
		font-size: 16px;
		position: absolute;
		width: auto;
		top: 50%;
		left: 50%;
		margin: 0 auto;
		margin-top: 30px;
		margin-left: -34px;
	}

	.wpp-preloader-dots {
		display: inline-block;
		width: 5px;
		overflow: hidden;
		vertical-align: bottom;
		animation: wpp-preloader-dots 1.5s linear infinite;
		transition: 1;
	}

	.wpp-preloader-car {
		position: absolute;
		width: 117px;
		height: 42px;
		left: 50%;
		top: 50%;
		margin-left: -59px;
		margin-top: -21px;
		border-bottom:2px solid #fff;
		padding-bottom:4px;
	}

	.wpp-preloader-strike {
		position: absolute;
		width: 11px;
		height: 1px;
		background: #ffffff;
		animation: wpp-preloader-strikes 0.2s linear infinite;
	}
	.wpp-preloader-strike2 {
		top: 11px;
		animation-delay: 0.05s;
	}
	.wpp-preloader-strike3 {
		top: 22px;
		animation-delay: 0.1s;
	}
	.wpp-preloader-strike4 {
		top: 33px;
		animation-delay: 0.15s;
	}
	.wpp-preloader-strike5 {
		top: 44px;
		animation-delay: 0.2s;
	}
	.wpp-preloader-car-detail {
		position: absolute;
		display: block;
		background: #fff;
	}
	.wpp-preloader-spoiler {
		width: 0;
		height: 0;
		top: 8px;
		background: none;
		border: 4px solid transparent;
		border-bottom: 8px solid #ffffff;
		border-left: 13px solid #ffffff;
	}
	.wpp-preloader-back {
		height: 20px;
		width: 92px;
		top: 15px;
		left: 0px;
	}
	.wpp-preloader-center {
		height: 35px;
		width: 75px;
		left: 12px;
		border-top-left-radius: 30px;
		border-top-right-radius: 45px 40px;
		border: 4px solid #ffffff;
		background: none;
		box-sizing: border-box;
	}
	.wpp-preloader-center1 {
		height: 35px;
		width: 35px;
		left: 12px;
		border-top-left-radius: 30px;
	}
	.wpp-preloader-front {
		height: 20px;
		width: 50px;
		top: 15px;
		left: 67px;
		border-top-right-radius: 50px 40px;
		border-bottom-right-radius: 10px;
	}
	.wpp-preloader-wheel {
		animation: wpp-preloader-spin 0.5s linear infinite;
		height: 20px;
		width: 20px;
		border-radius: 50%;
		top: 20px;
		left: 12px;
		border: 3px solid #000;
		background: linear-gradient(45deg, transparent 45%, #ffffff 46%, #ffffff 54%, transparent 55%), linear-gradient(-45deg, transparent 45%, #ffffff 46%, #ffffff 54%, transparent 55%), linear-gradient(90deg, transparent 45%, #ffffff 46%, #ffffff 54%, transparent 55%), linear-gradient(0deg, transparent 45%, #ffffff 46%, #ffffff 54%, transparent 55%), radial-gradient(#ffffff 29%, transparent 30%, transparent 50%, #ffffff 51%), #000;
	}
	.wpp-preloader-wheel2 {
		left: 82px;
	}

</style>
