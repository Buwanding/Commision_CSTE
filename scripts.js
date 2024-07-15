document
  .getElementById("loginForm")
  .addEventListener("submit", function (event) {
    event.preventDefault();
    const username = document.getElementById("username").value;
    const password = document.getElementById("password").value;

    // Add your login logic here. For now, we'll just log the inputs.
    console.log(`Username: ${username}, Password: ${password}`);
  });
