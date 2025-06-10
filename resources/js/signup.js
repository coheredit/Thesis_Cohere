document.addEventListener("DOMContentLoaded", function () {
  document.getElementById("signupForm").addEventListener("submit", function (event) {
      event.preventDefault(); // Prevents immediate form submission

      // Fetch form values
      let email = document.getElementById("email").value.trim();
      let firstName = document.getElementById("first_name").value.trim();
      let lastName = document.getElementById("last_name").value.trim();
      let contactNumber = document.getElementById("phone").value.trim();
      let password = document.getElementById("password").value;
      let confirmPassword = document.getElementById("confirm_password").value;
      
      // Get selected profile picture
      let selectedAvatar = document.querySelector('input[name="profile_picture"]:checked');
      let profilePicture = selectedAvatar ? selectedAvatar.value : 'default.png';

      // Validate contact number (must be 11 digits)
      if (!/^\d{11}$/.test(contactNumber)) {
          alert("Contact number must be exactly 11 digits.");
          return;
      }

      // Validate password match
      if (password !== confirmPassword) {
          alert("Passwords do not match.");
          return;
      }

      // Get avatar display name for confirmation
      let avatarName = selectedAvatar ? selectedAvatar.nextElementSibling.nextElementSibling.textContent : 'Default';

      // Confirmation alert before submission
      let confirmSignup = confirm(
          `Please confirm your details:\n\nEmail: ${email}\nFirst Name: ${firstName}\nLast Name: ${lastName}\nContact Number: ${contactNumber}\nProfile Picture: ${avatarName}\n\nAre you sure you want to proceed?`
      );

      if (confirmSignup) {
          this.submit(); // Submits the form
      }
  });
});