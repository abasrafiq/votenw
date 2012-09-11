<div id="containerLogin">
  <h1>Login</h1>
  <?php echo validation_errors(); ?>
  <?php echo form_open('verifylogin'); ?>
    
      <input type="text" size="20" id="code" name="code" value="12345" />

    <input type="submit" id="btnLogin" value="Anmelden" class="btn-norm" />
    
  </form>
</div>
<div class="clear"></div>
<br />