<!DOCTYPE html>
<html lang="en">
<head>
    <title>Reservation</title>
    <link rel="stylesheet" href="style.css">
    <script>
        function showSummary() {
            document.getElementById("reservationForm").style.display = "none";
            document.getElementById("summary").style.display = "block";
        }

        function showForm() {
            document.getElementById("reservationForm").style.display = "block";
            document.getElementById("summary").style.display = "none";
        }
    </script>
</head>
<body>

    <div class="sidebar">
        <a href="home.php">Home</a>
        <a href="profile.php">Company's Profile</a>
        <a href="reservation.php">Reservation</a>
        <a href="contacts.php">Contacts</a>
    </div>

    <div class="content">
        <h2>Reservation</h2>

        <!-- Reservation Form -->
        <form id="reservationForm" action="reservation.php" method="post">
            <label for="name">Contact Name:</label>
            <input type="text" name="name" id="name" required><br>

            <label for="number">Contact Number:</label>
            <input type="text" name="number" id="number" required><br>

            <label for="from">Reservation Date:</label><br>
            From: <input type="date" name="from" id="from" required><br>
            To: <input type="date" name="to" id="to" required><br>

            <label for="room">Room Type:</label><br>
            <input type="radio" name="room" value="Regular" required>Regular<br>
            <input type="radio" name="room" value="Deluxe">De Luxe<br>
            <input type="radio" name="room" value="Suite">Suite<br><br>

            <label for="capacity">Room Capacity:</label><br>
            <input type="radio" name="capacity" value="Family" required>Family<br>
            <input type="radio" name="capacity" value="Double">Double<br>
            <input type="radio" name="capacity" value="Single">Single<br><br>

            <label for="payment">Payment Type:</label><br>
            <input type="radio" name="payment" value="Cash" required>Cash<br>
            <input type="radio" name="payment" value="Cheque">Cheque<br>
            <input type="radio" name="payment" value="Credit">Credit Card<br><br>

            <input type="submit" value="Submit Reservation">
            <input type="reset" value="Clear Entry">
        </form>

        <div id="summary" style="display: none;">
            <h2>Reservation Summary</h2>
            <?php
            if ($_SERVER["REQUEST_METHOD"] == "POST") {
                $name = $_POST['name'];
                $number = $_POST['number'];
                $from = $_POST['from'];
                $to = $_POST['to'];
                $room = $_POST['room'] ?? null;
                $capacity = $_POST['capacity'] ?? null;
                $payment = $_POST['payment'] ?? null;

                $startDate = new DateTime($from);
                $endDate = new DateTime($to);
                $days = $startDate->diff($endDate)->days;

                if ($days <= 0) {
                    echo "Invalid reservation dates.";
                    exit();
                }

                $rates = [
                    "Single" => ["Regular" => 100, "Deluxe" => 300, "Suite" => 500],
                    "Double" => ["Regular" => 200, "Deluxe" => 500, "Suite" => 800],
                    "Family" => ["Regular" => 500, "Deluxe" => 750, "Suite" => 1000]
                ];
                $ratePerDay = $rates[$capacity][$room];
                $subtotal = $ratePerDay * $days;

                $discount = 0;
                $additionalCharge = 0;
                if ($payment === "Cash") {
                    if ($days >= 6) {
                        $discount = $subtotal * 0.15;
                    } elseif ($days >= 3) {
                        $discount = $subtotal * 0.10;
                    }
                } elseif ($payment === "Credit") {
                    $additionalCharge = $subtotal * 0.10;
                }elseif ($payment === "Cheque") {
                    $additionalCharge = $subtotal * 0.05;
                }
                $total = $subtotal - $discount + $additionalCharge;

                echo "Name: $name<br>";
                echo "Contact Number: $number<br>";
                echo "Room Type: $room<br>";
                echo "Room Capacity: $capacity<br>";
                echo "Payment Type: $payment<br>";
                echo "Reservation Dates: $from to $to ($days days)<br>";
                echo "Rate per Day: $$ratePerDay<br>";
                echo "Subtotal: $$subtotal<br>";
                echo "Discount: $$discount<br>";
                echo "Additional Charge: $$additionalCharge<br>";
                echo "Total Bill: $$total<br>";
            }
            ?>
            <br>
            <button onclick="showForm()">Return to Reservation Form</button>
        </div>

        <?php if ($_SERVER["REQUEST_METHOD"] == "POST") { ?>
            <script>showSummary();</script>
        <?php } ?>
    </div>

</body>
</html>
