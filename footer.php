
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js" integrity="sha384-YvpcrYf0tY3lHB60NNkmXc5s9fDVZLESaAA55NDzOxhy9GkcIdslK1eN7N6jIeHz" crossorigin="anonymous"></script>
    <script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.11.8/dist/umd/popper.min.js" integrity="sha384-I7E8VVD/ismYTF4hNIPjVp/Zjvgyol6VFvRkX/vR+Vc4jQkC+hVqc2pM8ODewa9r" crossorigin="anonymous"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.min.js" integrity="sha384-0pUGZvbkm6XF6gxjEnlmuGrJXVbNuzT9qBBavbLwCsOGabYfZo0T0to5eqruptLy" crossorigin="anonymous"></script>
    <script src="javascript/script.js"></script>


    <script>
      function showToast(message, position, type) {
        const toast = document.getElementById("toast");
        toast.className = toast.className + " show";

        if (message) toast.innerText = message;

        if (position !== "") toast.className = toast.className + ` ${position}`;
        if (type !== "") toast.className = toast.className + ` ${type}`;

        setTimeout(function () {
          toast.className = toast.className.replace(" show", "");
        }, 3000);
      }

      // Hide the alert message after 3 seconds
      setTimeout(function() {
        var alert = document.querySelector('.alert');
        if (alert) {
          var bsAlert = new bootstrap.Alert(alert);
          bsAlert.close();
        }
      }, 3000);


      document.addEventListener("DOMContentLoaded", function() {
            tinymce.init({
                selector: 'textarea.wysiwyg-editor',
                plugins: 'advlist autolink lists link image charmap print preview hr anchor pagebreak',
                toolbar_mode: 'floating',
            });
        });
      
    </script>
    <footer>
      <div class="d-flex flex-column flex-sm-row justify-content-center text-center mt-5 py-4 my-4 border-top">
        <p>&copy; Fields of Mistria RPG Archive MRegacho, Inc. All rights reserved.</p>
      </div>
    </footer>
  </body>
</html>