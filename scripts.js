// Add an event listener to the login form to handle form submission
document
  .getElementById("loginForm")
  .addEventListener("submit", function (event) {
    
    // Prevent the default form submission behavior (e.g., refreshing the page)
    event.preventDefault();
    
    // Get the values of the username and password input fields
    const username = document.getElementById("username").value;
    const password = document.getElementById("password").value;

    // Placeholder for the actual login logic (e.g., sending data to the server)
    // Currently, it just logs the input values to the console
    console.log(`Username: ${username}, Password: ${password}`);
  });
