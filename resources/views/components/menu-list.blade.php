<div class="relative">
    <x-button-secondary class="menu-button" id="{{$id}}">
        <svg xmlns="http://www.w3.org/2000/svg" class="h-8 w-8 open" fill="none" viewBox="0 0 24 24" stroke="currentColor">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 12h16M4 18h16" />
        </svg>
        <svg xmlns="http://www.w3.org/2000/svg" class="h-8 w-8 close" fill="none" viewBox="0 0 24 24" stroke="currentColor">
          <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" />
        </svg>
    </x-button-secondary>

    <ul class="menu-list bg-white">
        {{$slot}}
    </ul>
</div>

@push('scripts')
    <script>
        document.getElementById('{{$id}}').addEventListener('click', function(){
            if(this.classList.contains('show')) {
                this.classList.remove('show');
                this.nextElementSibling.classList.remove('show');
            } else {
                let menuList = document.getElementsByClassName('menu-list');
                for(let i = 0; i < menuList.length; i++) {
                    menuList[i].classList.remove('show');
                    menuList[i].previousElementSibling.classList.remove('show');
                }
                this.classList.add('show');
                this.nextElementSibling.classList.add('show');
            }
        }, false);
    </script>
@endpush

