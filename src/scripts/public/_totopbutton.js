/* кнопка наверх */
jQuery( document ).ready( function () {
	var $w = jQuery( window ),
		$toTopBtn = jQuery( '<button>', {
			class: 'totopbutton',
			id: 'toTopBtn',
		} ).appendTo( jQuery( 'body' ) );
	function _btnHide() {
		if ( $w.scrollTop() > screen.height * 0.5) {
			$toTopBtn.show();
		} else {
			$toTopBtn.hide();
		}
	}
	function _toTopBtnClick() {
		jQuery( 'body, html' ).animate( {
			scrollTop: 0
		}, 500 );
		return false;
	}
	_btnHide();
	$w.bind( 'scroll', _btnHide );
	$toTopBtn.bind( 'click', _toTopBtnClick );
} );
