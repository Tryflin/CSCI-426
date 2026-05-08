async function OnSignup()
{
    const username = document.getElementById("user").value;
    const password = document.getElementById("pass").value;

    const response = await fetch("signup.php",
    {
        method: "POST",
        headers:
        {
            "Content-Type": "application/json"
        },
        body: JSON.stringify({ username, password })
    });

    const data = await response.json();

    console.log(data);
}