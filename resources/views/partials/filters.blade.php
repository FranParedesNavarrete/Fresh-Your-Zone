<div class="d-flex flex-column">
  <button class="rounded d-md-block d-md-none text-dark"  data-bs-toggle="collapse" data-bs-target="#mobileSidebar" aria-expanded="false" aria-controls="mobileSidebar" style="background-color: #d4cdc5 !important;">Filtros</button>

  <aside class="p-3 filter-container collapse d-md-block" id="mobileSidebar" style="width: 280px;">
    <!-- Boolean Filters -->
    <div class="mb-4">
      <h5 class="fw-bold d-none d-md-block">Filtros</h5>
      <div class="form-check form-switch">
        <input class="form-check-input" type="checkbox" id="available" {{ $request->input('available') ? 'checked' : '' }} onclick="window.location.href='{{ $request->fullUrlWithQuery(['available' => $request->input('available') ? null : 'true']) }}'">
        <label class="form-check-label" for="available">Disponibles</label>
      </div>

      <div class="form-check form-switch">
        <input class="form-check-input" type="checkbox" id="reset" onclick="window.location.href='/products'">
        <label class="form-check-label" for="reset">Restablecer Filtros</label>
      </div>
    </div>

    <!-- Categorías con acordeón -->
    <div class="filter-categories">
      <div class="accordion mb-4" id="categoryAccordion">
        <div class="accordion-item">
          <h2 class="accordion-header" id="categoryHeading">
            <button class="accordion-button collapsed" type="button" data-bs-toggle="collapse" data-bs-target="#categoryCollapse">Categorías</button>
          </h2>
          <div id="categoryCollapse" class="accordion-collapse collapse" data-bs-parent="#categoryAccordion">
            <div class="accordion-body">
              <ul class="list-unstyled">
                <li><a class="text-dark" href="{{ $request->fullUrlWithQuery(['category' => null]) }}">Todas las categorías</a></li>
                @foreach ($categories as $category)
                  <li><a class="text-dark" href="{{ $request->fullUrlWithQuery(['category' => $category]) }}">{{ ucfirst($category) }}</a></li>
                @endforeach
              </ul>
            </div>
          </div>
        </div>
      </div>

      <!-- Estados con acordeón -->
      <div class="accordion mb-4" id="stateAccordion">
        <div class="accordion-item">
          <h2 class="accordion-header" id="stateHeading">
            <button class="accordion-button collapsed" type="button" data-bs-toggle="collapse" data-bs-target="#stateCollapse">Estados</button>
          </h2>
          <div id="stateCollapse" class="accordion-collapse collapse" data-bs-parent="#stateAccordion">
            <div class="accordion-body">
              <ul class="list-unstyled">
                <li><a href="{{ $request->fullUrlWithQuery(['state' => null]) }}">Todos los estados</a></li>
                @foreach($states as $state)
                  <li><a class="text-dark" href="{{ $request->fullUrlWithQuery(['state' => $state]) }}">{{ ucfirst($state) }}</a></li>
                @endforeach
              </ul>
            </div>
          </div>
        </div>
      </div>

      <!-- Precio con acordeón -->
      <div class="accordion" id="priceAccordion">
        <div class="accordion-item">
          <h2 class="accordion-header" id="priceHeading">
            <button class="accordion-button collapsed" type="button" data-bs-toggle="collapse" data-bs-target="#priceCollapse">Precios</button>
          </h2>
          <div id="priceCollapse" class="accordion-collapse collapse" data-bs-parent="#priceAccordion">
            <div class="accordion-body">
              <ul class="list-unstyled">
                <div class="mb-3">
                  <label for="price" class="form-label">Seleccionar precio exacto:</label>
                  <input type="range" name="price" id="price" min="0" max="1000" step="1"
                        value="{{ $request->input('price') }}" class="form-range"
                        oninput="document.getElementById('priceValue').innerText = this.value"
                        onchange="window.location.href='{{ $request->fullUrlWithQuery(['price' => '']) }}'.replace('price=', 'price=' + this.value)">
                  <span id="priceValue">{{ $request->input('price') ?? '500' }}</span> €
                </div>
                <li><a href="{{ $request->fullUrlWithQuery(['price' => null]) }}">Todos los precios</a></li>
                <li><a class="text-dark" href="{{ $request->fullUrlWithQuery(['price' => '0-100']) }}">0 - 100 €</a></li>
                <li><a class="text-dark" href="{{ $request->fullUrlWithQuery(['price' => '100-200']) }}">100 - 200 €</a></li>
                <li><a class="text-dark" href="{{ $request->fullUrlWithQuery(['price' => '200-300']) }}">200 - 300 €</a></li>
                <li><a class="text-dark" href="{{ $request->fullUrlWithQuery(['price' => '300-400']) }}">300 - 400 €</a></li>
                <li><a class="text-dark" href="{{ $request->fullUrlWithQuery(['price' => '400-500']) }}">400 - 500 €</a></li>
                <li><a class="text-dark" href="{{ $request->fullUrlWithQuery(['price' => '500-10000']) }}">500 € o más</a></li> 
              </ul>
            </div>
          </div>
        </div>
      </div>
    </div>
  </aside>

</div>
