<div>
    {{-- The Master doesn't talk, he acts. --}}

    @php Carbon\Carbon::setLocale('es'); @endphp


    <div class="collapse navbar-collapse ml-3" id="notificacion">
        <ul class="navbar-nav mt-3">
            <li class="nav-item dropdown">
                <a class="nav-link dropdown-toggle p-0" href="#" id="notificaciones" role="button"
                    wire:click.prevent="resetNotification()" 
                    data-bs-toggle="dropdown" aria-expanded="false">
                    <i class="far fa-bell fs-3" style="position: relative; top:-6px;"></i>
                    
                    @if (Auth()->user()->notification)
                        <span id="bell-notification"> {{ Auth()->user()->notification }}</span>
                        <span class="visually-hidden">notificaciones sin leer</span>
                    @endif

                </a>
                <ul wire:ignore.self class="dropdown-menu dropdown-menu-end dropdown-menu-start"
                    aria-labelledby="notificaciones" id="menuNotificaciones"
                    style="width: 340px; max-height:calc(100vh - 8rem); overflow-y: auto;">

                    @if ($this->notifications->count())
                        @foreach ($this->notifications as $notification)
                            <li class="py-2" {{ !$notification->read_at ? 'style=background-color:#f4f4f4' : '' }}
                                wire:click="readNotification('{{ $notification->id }}')">
                                <div class="px-3 fw-bold fs-6" style="color: #232222;">
                                    <span>{{ $notification->data['subject'] }}</span>
                                </div>
                                <a class="text-sm text-break dropdown-item"
                                    href={{ $notification->data['url'] }}>{!! nl2br($notification->data['message']) !!}
                                    <br>
                                    <div class="d-flex justify-content-end">
                                        <span class="text-end badge bg-help-naranja text-wrap fw-normal">{{ $notification->created_at->diffForHumans() }}</span>
                                    </div>
                                </a>
                            </li>
                            <div class="m-1"></div>
                        @endforeach
                        @if (Auth()->user()->notifications->count() > $count)
                            <div class="d-flex justify-content-center mt-2">
                                <a href="#" wire:click="incrementCount()" style="color:#232222;"
                                    class="fw-bold text-sm" onclick="event.stopPropagation();">
                                    Ver más notificaciones
                                </a>
                            </div>
                        @endif
                    @else
                        <div class="row text-center">
                            <div class="col-12 py-3">
                                <img src="{{ asset('images/logos/campana.png') }}" alt="">
                            </div>
                            <div class="col-12 px-4">
                                <span class="text-secondary">Estas al día, no tienes ninguna notificación
                                    disponible.</span>
                            </div>
                        </div>
                    @endif
                </ul>
            </li>
        </ul>
    </div>
</div>
