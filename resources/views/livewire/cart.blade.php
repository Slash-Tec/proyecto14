@props(['size' => 24, 'color' => 'gray'])

@php
    switch ($color){
        case'gray':
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

<svg xmlns="https://www.w3.org/2000/svg" x="0px" y="0px"
     width="{{ $size }}" height="{{ $size }}"
     viewBox="0 0 172 172"
     style="fill:#000000;">
    <g fill="none" fill-rule="nonzero" stroke="none" stroke-width="1" stroke-linecap="butt" stroke-linejoin="miter"
       stroke-miterlimit="10" stroke-dasharray="" stroke-dashoffset="0" font-family="none" font-weight="none"
       font-size="none" text-anchor="none" style="mix-blend-mode: normal">
       <path d="M0,172v-172h172v172z" fill="none"></path>
        <g fill="{{ $col }}">
            ...
        </g>
    </g>
</svg>
