<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <style>
        body {
            display: flex;
            justify-content: center;
            align-items: center;
            flex-direction: column;
            height: 100vh;
            width: 100vw;
            overflow: hidden;
        }

        .pay {
            font-size: 1.5rem;
            cursor: pointer;
        }
    </style>
    <title>GoPay</title>
</head>

<body>
    <?php
    require_once __DIR__ . '/init-gopay.php';

    $embedJs = $gopay->urlToEmbedJs();
    ?>

    <form id="payment-form" action="/create-payment.php" method="POST">
        <script src="<?php echo $embedJs; ?>"></script>
        <button type="submit" class="pay">Pay</button>
    </form>

    <p id="payment-status"></p>

    <script>
        document.addEventListener("DOMContentLoaded", () => {
            const paymentForm = document.querySelector("#payment-form");
            paymentForm.addEventListener("submit", async (event) => {
                event.preventDefault();
                const createResult = await createPayment(paymentForm);
                if (createResult && createResult.gw_url) {
                    window.location.href = createResult.gw_url;
                }
            });

            const urlParams = new URLSearchParams(window.location.search);
            if (urlParams.has('id')) {
                const paymentId = urlParams.get('id');
                checkPaymentStatus(paymentId);
            }
        });

        const createPayment = async (form) => {
            try {
                const formData = new FormData(form);
                const formObject = Object.fromEntries(formData.entries());

                const createResponse = await fetch(form.action, {
                    method: "POST",
                    headers: {
                        Accept: "application/json",
                        "Content-Type": "application/json"
                    },
                    body: JSON.stringify(formObject)
                });

                const createResult = await createResponse.json();
                if (!createResponse.ok) {
                    console.error(
                        `Server returned: ${createResponse.status}: ${createResponse.statusText}:\n${createResult}`
                    );
                    return;
                }
                return createResult;
            } catch (err) {
                console.error(err);
            }
        };

        const checkPaymentStatus = async (paymentId) => {
            try {
                const statusResponse = await fetch(`/status.php?id=${paymentId}`, {
                    method: "GET",
                    headers: {
                        Accept: "application/json"
                    }
                });

                const statusResult = await statusResponse.json();
                if (!statusResponse.ok) {
                    console.error(
                        `Server returned: ${statusResponse.status}: ${statusResponse.statusText}:\n${statusResult}`
                    );
                    return;
                }

                document.getElementById('payment-status').textContent = `ID: ${paymentId} > ${statusResult.state}`;
            } catch (err) {
                console.error(err);
            }
        };
    </script>
</body>

</html>