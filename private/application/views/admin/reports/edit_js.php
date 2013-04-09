/**
 * Decrypts personal data on edit.php page
 *
 * PHP version 5
 * LICENSE: This source file is subject to LGPL license 
 * that is available through the world-wide-web at the following URI:
 * http://www.gnu.org/copyleft/lesser.html
 * @author     Ushahidi Team <team@ushahidi.com> 
 * @package    Ushahidi - http://source.ushahididev.com
 * @module     API Controller
 * @copyright  Ushahidi - http://www.ushahidi.com
 * @license    http://www.gnu.org/copyleft/lesser.html GNU Lesser General Public License (LGPL) 
 */

<script type="text/javascript" src="<?php echo URL::base(); ?>media/js/Barrett.js"> </script> 
<script type="text/javascript" src="<?php echo URL::base(); ?>media/js/BigInt.js"> </script> 
<script type="text/javascript" src="<?php echo URL::base(); ?>media/js/RSA.js"> </script> 

 <script type="text/javascript">
 function decrypt() {
  	var rsa = new RSAKey();
  	var privkey = $('#decryptkey').val();
  	console.log('rsa');
  	console.log(rsa);
  	console.log(privkey);
	rsa.setPrivateEx(dr.n.value, dr.e.value, dr.d.value, dr.p.value, dr.q.value, dr.dmp1.value, dr.dmq1.value, dr.coeff.value);
	if(document.rsatest.ciphertext.value.length == 0) {
	  return;
	}
  var res = rsa.decrypt(document.rsatest.ciphertext.value);
  var after = new Date();
  if(res == null) {
    document.rsatest.decrypted.value = "*** Invalid Ciphertext ***";
    do_status("Decryption failed");
  }
  else {
    document.rsatest.decrypted.value = res;
    do_status("Decryption Time: " + (after - before) + "ms");
  }
}

 $(document).ready(function(){
		$('#delete_button').click(function(){
			decrypt();
	   });
});

 
</script>