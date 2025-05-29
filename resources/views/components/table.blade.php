<div class="table-responsive" id="table">
    <table class="table table-striped table-bordered table-hover">
        <thead>
            <tr>
                @foreach ($columns as $column)
                    @if ($column == 'precio') 
                        <th class="price-row">{{ ucfirst($column) }}</th>
                    @else
                        <th>{{ ucfirst($column) }}</th>
                    @endif
                @endforeach
            </tr>
        </thead>
        <tbody class="text-center">
            @forelse ($data as $row)
                <tr>
                    @foreach ($columns as $column)
                        @switch($column)
                            @case('fecha')
                                <td>{{ \Carbon\Carbon::parse($row->created_at)->format('d/m/Y H:i') ?? '-' }}</td>
                                @break
                            @case('producto')
                                <td class="product-row">{{ $row->product->name ?? $row->name }}</td>
                                @break

                            @case('precio')
                                <td>{{ $row->product->price ?? $row->price }} €</td>
                                @break

                            @case('descripción')
                                <td class="product-row">{{ $row->product->description ?? $row->description }}</td>
                                @break

                            @case('estado')
                                <td>{{ $row->status ?? $row->state }}</td>
                                @break
                            @case('tipo')
                                <td>{{ $row->type }}</td>
                                @break
                            @case('contenido')
                                <td>{{ $row->subject }}</td>
                                @break
                            @case('imagenes')
                                <td>
                                    @if(!empty($row->images))
                                        <img class="product-image-table" src="{{ asset('storage/' . explode('|', $row->images)[0]) }}" alt="Imagen de {{ $row->name }}">
                                    @else
                                        -
                                    @endif
                                </td>
                                @break

                            @default

                            @if ($column == 'precio')
                                <td>{{ $row->column }} €</td>
                            @endif
                            
                            <td>{{ $row->$column ?? '-' }}</td>
                        @endswitch
                    @endforeach
                </tr>
            @empty
                <tr>
                    <td colspan="{{ count($columns) }}" class="text-center">No hay datos</td>
                </tr>
            @endforelse
        </tbody>
    </table>
</div>
