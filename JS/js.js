 const burger = document.querySelector('.burger');
            const nav = document.querySelector('nav');
            burger.addEventListener('click', () => {
                nav.classList.toggle('active');
            });

            burger.addEventListener('click', () => {
                burger.classList.toggle('open');
            });



/**********************************************/

document.getElementById("contact-Form").addEventListener("submit", function (e) {
    e.preventDefault();

    // Get the form data
    const form = e.target;
    const statusMessage = document.getElementById("statusMessage");

    // Validate required fields
    const name = form.name.value.trim();
    const email = form.email.value.trim();
    const phone = form.phone.value.trim();
    const service = form.service.value.trim();
    const message = form.message.value.trim();

    if (!name || !email || !service || !message) {
        statusMessage.style.color = "red";
        statusMessage.style.display = "block";
        statusMessage.textContent = "⚠️ Please fill out all required fields.";
        return;
    }

    // Send email to YOU
    emailjs.send("service_ttgbify", "template_qg6gwn8", {
        to_name: "Yazeed Edrees", // Admin name
        from_name: name,
        from_email: email,
        phone: phone,
        service: service,
        message: message
    })
    .then(() => {
        // Send confirmation email to USER
        return emailjs.send("YOUR_SERVICE_ID", "YOUR_CONFIRM_TEMPLATE_ID", {
            user_name: name,
            user_email: email,
            user_message: message
        });
    })
    .then(() => {
        statusMessage.style.color = "green";
        statusMessage.style.display = "block";
        statusMessage.textContent = "✅ Message sent successfully! Please check your email.";
        form.reset();
    })
    .catch((error) => {
        console.error("EmailJS Error:", error);
        statusMessage.style.color = "red";
        statusMessage.style.display = "block";
        statusMessage.textContent = "❌ Message failed to send. Please try again later.";
    });
});
