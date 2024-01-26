<div class="flex-1 relative">
    <form action="{{ route('search') }}" autocomplete="off" method="get">
        <x-jet-input name="none" wire:model="search" type="text" class="flex w-full"
             placeholder="¿Estás buscando algún producto?"></x-jet-input>
        <button class="absolute top-0 right-0 w-12 h-full bg-orange-500 flex items-center justify-center rounded-r-md">
            <x-search size="35" color="white"></x-search>
        </button>
    </form>

    <div class="absolute w-full mt-1 hidden" :class="{ 'hidden' : !$wire.open }" @click.away="$wire.open = false">
        <div class="bg-white rounded-lg shadow-lg">
            <div class="px-4 py-3 space-y-1">
                @forelse ($products as $product)
                    <a href="{{ route('products.show', $product) }}" class="flex">
                        <img class="w-16 h-12 object-cover" src="{{ Storage::url($product->images->first()->url) }}">
                        <div class="ml-4 text-gray-700">
                            <p class="text-lg font-semibold leading-5">{{ $product->name }}</p>
                            <p>Categoria: {{$product->subcategory->category->name}}</p>
                        </div>
                    </a>
                @empty
                    <p class="text-lg leading-5">
                        No existe ningún registro con los parámetros especificados
                    </p>
                @endforelse
            </div>
        </div>
    </div>

    @props(['size' => 50, 'color' => 'gray'])

    @php
        switch ($color){
            case 'gray':
                $col = "#374151";
                break;
            case 'white':
                $col = "#ffffff";
                break;
            default:
                $col = "#374151";
                break;
        }
    @endphp

    <svg xmlns="http://www.w3.org/2000/svg" x="0px" y="0px" width="{{ $size }}" height="{{ $size  }}" viewBox="0,0,256,256">
        <g fill="{{ $col }}"><g transform="scale(2,2)"><path d="M52.34961,14.40039c-9.725,0 -19.44961,3.69961 -26.84961,11.09961c-14.8,14.8 -14.8,38.89922 0,53.69922c7.4,7.4 17.10039,11.10156 26.90039,11.10156c9.8,0 19.50039,-3.70156 26.90039,-11.10156c14.7,-14.8 14.69844,-38.89922 -0.10156,-53.69922c-7.4,-7.4 -17.12461,-11.09961 -26.84961,-11.09961zM52.30078,20.30078c8.2,0 16.39961,3.09844 22.59961,9.39844c12.5,12.5 12.49961,32.80078 0.09961,45.30078c-12.5,12.5 -32.80078,12.5 -45.30078,0c-12.5,-12.5 -12.5,-32.80078 0,-45.30078c6.2,-6.2 14.40156,-9.39844 22.60156,-9.39844zM52.30078,26.30078c-6.9,0 -13.40078,2.69922 -18.30078,7.69922c-4.7,4.7 -7.29961,10.80039 -7.59961,17.40039c-0.1,1.7 1.20039,2.99961 2.90039,3.09961h0.09961c1.6,0 2.9,-1.30039 3,-2.90039c0.2,-5.1 2.29883,-9.80039 5.79883,-13.40039c3.8,-3.8 8.80156,-5.89844 14.10156,-5.89844c1.7,0 3,-1.3 3,-3c0,-1.7 -1.3,-3 -3,-3zM35,64c-1.65685,0 -3,1.34315 -3,3c0,1.65685 1.34315,3 3,3c1.65685,0 3,-1.34315 3,-3c0,-1.65685 -1.34315,-3 -3,-3zM83.36328,80.5c-0.7625,0 -1.5125,0.30039 -2.0625,0.90039c-1.2,1.2 -1.2,3.09922 0,4.19922l2.5,2.5c-0.6,1.2 -0.90039,2.50039 -0.90039,3.90039c0,2.4 0.89961,4.70039 2.59961,6.40039l12.80078,12.59961c1.8,1.8 4.09844,2.69922 6.39844,2.69922c2.3,0 4.60039,-0.89961 6.40039,-2.59961c3.5,-3.5 3.5,-9.19922 0,-12.69922l-12.79883,-12.80078c-1.7,-1.7 -4.00039,-2.59961 -6.40039,-2.59961c-1.4,0 -2.70039,0.30039 -3.90039,0.90039l-2.5,-2.5c-0.6,-0.6 -1.37422,-0.90039 -2.13672,-0.90039zM91.90039,88.90039c0.8,0 1.59961,0.30039 2.09961,0.90039l12.69922,12.69922c1.2,1.2 1.2,3.09922 0,4.19922c-1.2,1.2 -3.09922,1.2 -4.19922,0l-12.69922,-12.59961c-0.6,-0.6 -0.90039,-1.39922 -0.90039,-2.19922c0,-0.8 0.30039,-1.59961 0.90039,-2.09961c0.6,-0.6 1.29961,-0.90039 2.09961,-0.90039z"></path></g></g>
    </svg>

</div>
