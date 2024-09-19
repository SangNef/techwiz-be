<script>
    document.getElementById('addNewBtn').addEventListener('click', function() {
            document.getElementById('addNewPopup').classList.remove('hidden');
            document.getElementById('addNewPopup').classList.add('flex');
        });
    
        document.getElementById('cancelBtn').addEventListener('click', function() {
            document.getElementById('addNewPopup').classList.add('hidden');
            document.getElementById('addNewPopup').classList.remove('flex');
        });
</script>