<!-- Footer-->
<footer class="content-footer footer bg-footer-theme">
  <div class="{{ (!empty($containerNav) ? $containerNav : 'container-fluid') }} d-flex flex-wrap justify-content-between py-2 flex-md-row flex-column">
    <div class="mb-2 mb-md-0">
      © <script>
        document.write(new Date().getFullYear())
      </script>
Film Company 💙 </div>
    {{-- <div>
      <a href="{{ config('variables.documentation') ? config('variables.documentation') : '#' }}" target="_blank" class="footer-link me-4">Documentation</a>
    </div> --}}
  </div>
</footer>
<!--/ Footer-->