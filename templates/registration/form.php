<form action="/user/registration" method="post">
    <p>Please fill in this form to create an account.</p>
    <div>
        <label for="firstname">Fist name</label>
        <input type="text" placeholder="Enter Fist name" name="firstname" id="firstname" required>    
    </div>
    <div>
        <label for="lastname">Last name</label>
        <input type="text" placeholder="Enter Last name" name="lastname" id="lastname" required>
    </div>
    <div>
        <label for="email">Email</label>
        <input type="email" placeholder="Enter Email" name="email" id="email" required>
    </div>
    <div>
        <label for="phone">Phone</label>
        <input type="phone" placeholder="Enter Phone Number" name="phone" id="phone" required>
    </div>
    <div>
        <label for="psw">Password</label>
        <input type="password" name="psw" id="psw" placeholder="Enter Password"
               pattern="(?=.*\d)(?=.*[a-z])(?=.*[A-Z]).{8,}"
               title="Must contain at least one number and one uppercase and lowercase letter, and at least 8 or more characters"
               required>
    </div>
    <div>
        <label for="confirm">Confirm password</label>
        <input type="password" placeholder="Confirm password" name="confirm" id="confirm" required>
    </div>
    <p>By creating an account you agree to our <a href="#">Terms & Privacy</a>.</p>
    <div class="g-recaptcha" data-sitekey="0000000000000000000000" style="margin-bottom:1em;">
    </div>
    <input type="submit" value="Register">
    <input type="reset" value="Reset">
    <p>Already have an account? <a href="home">Sign in</a>.</p>
</form>

<!-- captcha; -->