<div id="containerLogin">
  <h1>Login</h1>
  <?php echo validation_errors(); ?>
  <?php echo form_open('verifylogin'); ?>
    
    <div class="form-row">
      <label for="code">Bitte individuellen Code eingeben:</label>
      <input type="text" size="20" id="code" name="code" value="12345" />
    </div>

    <input type="submit" value="Anmelden" class="btn-norm" />
    
  </form>
</div>