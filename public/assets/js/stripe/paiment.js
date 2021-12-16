
const stripe = Stripe(stripePublicKey);
const elements = stripe.elements();
var style = {
    base: {
        color: "#32325d",
        fontFamily: 'Arial, sans-serif',
        fontSmoothing: "antialiased",
        fontSize: "16px",
        "::placeholder": {
            color: "#32325d"
        }
    },
    invalid: {
        fontFamily: 'Arial, sans-serif',
        color: "#fa755a",
        iconColor: "#fa755a"
    }
};
const card = elements.create("card", { style: style });

card.mount("#card-element");
card.on("change", function (event) {

    document.querySelector("button").disabled = event.empty;
    document.querySelector("#card-error").textContent = event.error ? event.error.message : "";
});

const form = document.getElementById("payment-form");
form.addEventListener("submit", function(event) {
    event.preventDefault();

    //payWithCard(stripe, card, data.clientSecret);
    stripe
        .confirmCardPayment(clientSecret, {
            payment_method: {
                card: card
            }
        })
        .then(function(result) {
            if (result.error) {
                //showError(result.error.message);
                console.log(result.error.message)
            } else {
                //orderComplete(result.paymentIntent.id);
                //console.log(result);
                window.location.href = redirectAfterSuccessUrl;
            }
        });
});