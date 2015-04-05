// JavaScript Document

function keyHandler(e) {
	// << 37 39 >>
	if (e.keyCode == 37) {
		document.location = prevUrl;
	} else if (e.keyCode == 39) {
		document.location = nextUrl;
	}
}

document.onkeydown = keyHandler;