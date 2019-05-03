$(document).ready(function () {
    var stripe = Stripe('pk_live_yHDrYE1fpgogeJYnKl8TmNkd');

    var elements = stripe.elements();
    var cardElement = elements.create('card');
    cardElement.mount('#card-element');
});