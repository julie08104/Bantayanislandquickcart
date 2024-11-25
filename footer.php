
<script>
    function validatePhoneInput(event) {
        let input = event.target.value;
        if (!input.startsWith("09") && input.length > 2) {
            event.target.value = "09";
        }
        if (input.length > 11) {
            event.target.value = input.slice(0, 11);
        }
        if (!/^\d*$/.test(input)) {
            event.target.value = input.replace(/[^\d]/g, '');
        }
    }
</script>
</body>
</html>