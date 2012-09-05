<div id="containerLogin">
  <h1>Login</h1>
  <?php echo validation_errors(); ?>
  <?php echo form_open('verifylogin'); ?>
    
    <div class="form-row">
      <label for="username">Username</label>
      <input type="text" size="20" id="username" name="username"/>
    </div>

    <div class="form-row">
      <label for="password">Password</label>
      <input type="password" size="20" id="passowrd" name="password"/>
    </div>

    <input type="submit" value="Login" class="btn-norm" />
    
  </form>
</div>