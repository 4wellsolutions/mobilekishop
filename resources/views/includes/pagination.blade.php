<div class="col-auto mx-auto">
  <nav class="toolboxs toolbox-paginations my-3 text-center">
    {{ $products->withQueryString()->links() }}
  </nav>
</div>
