<style lang="scss">
	*,
	::after,
	::before {
		box-sizing: border-box;
	}

	#page-loader {
		background: rgba(105, 151, 219, 0.6);
		overflow: hidden;
		position: fixed;
		top: 0;
		bottom: 0;
		left: 0;
		right: 0;
		z-index: 9999;
	}

	#page-loader h4 {
		position: absolute;
		top: calc(50% + 100px);
		left: calc(50% + 20px);
		margin: 0;
		font-weight: 200;
		opacity: 0.5;
		font-family: fantasy;
		color: #fff;
		transform: translateX(-50%);
		font-size: 25px;
		text-shadow: -2px 1px 0 #1d1a1b, -6px 3px 8px rgba(0, 0, 0, 0.5);
	}

	#loader {
		/* Uncomment this to make it run! */
		/* animation: loader 10s linear infinite; */
		position: absolute;
		top: calc(50% - 20px);
		left: calc(50% - 20px);
	}

	#box {
		width: 70px;
		height: 70px;
		background: transparent;
		transform: rotate(0deg);
		animation: animate 2000ms linear infinite;
		position: absolute;
		top: 0;
		left: 0;
		border-radius: 3px;
		background-image: url(/img/round-logo.png);
		background-size: contain;
		background-repeat: no-repeat;
	}

	#shadow {
		width: 70px;
		height: 5px;
		background: #000;
		opacity: 0.1;
		position: absolute;
		top: 95px;
		left: 0;
		border-radius: 50%;
		animation: shadow 0.5s linear infinite;
	}

	@keyframes loader {
		0% {
			left: -100px;
		}

		100% {
			left: 110%;
		}
	}

	@keyframes animate {
		0% {
			transform: rotate(0deg);
		}

		25% {
			transform: translateY(9px) rotate(90deg);
		}

		50% {
			transform: translateY(18px) scale(1, 0.9) rotate(180deg);
		}

		75% {
			transform: translateY(9px) rotate(270deg);
		}

		100% {
			transform: translateY(0) rotate(359deg);
		}
	}

	@keyframes rotate {
		from {
			transform: rotate(0deg);
		}

		to {
			transform: rotate(350deg);
		}
	}

	@keyframes shadow {
		50% {
			transform: scale(1.2, 1);
		}
	}

</style>

<div id="page-loader">
	<div id="loader">
		<div id="shadow"></div>
		<div id="box"></div>
	</div>
	<h4>loading...</h4>
</div>
