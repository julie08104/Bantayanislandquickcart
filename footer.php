<script src="https://cdn.jsdelivr.net/npm/flowbite@2.5.1/dist/flowbite.min.js"></script>
<script src="https://cdn.datatables.net/1.12.1/js/jquery.dataTables.min.js"></script>
<script src="https://cdn.datatables.net/responsive/2.4.0/js/dataTables.responsive.min.js"></script>
<script src="https://www.google.com/recaptcha/api.js"></script>
<script src="https://cdn.jsdelivr.net/npm/swiper@11/swiper-bundle.min.js"></script>
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