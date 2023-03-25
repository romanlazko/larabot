<section class="p-4 sm:p-8 bg-white shadow sm:rounded-lg ">
    <header>
        <h2 class="text-lg font-medium text-gray-900">
            {{ __('WebHook setup') }}
        </h2>
        <p>
            Use this form to specify a URL and receive incoming updates via an outgoing webhook.
        </p>
    </header>

    <form method="post" action="{{ route('bots.store') }}" class="mt-6 space-y-6">
        @csrf

        <div>
            <x-input-label for="url" :value="__('Messanger')" />
            <x-select id="messanger-select" class="mt-1 block w-full" :options="[
                'Выберите мессенджер' => null,
                'Telegram'  => url('/api/telegram'), 
                'Viber'     => url('/api/viber'), 
                'Whatsapp'  => url('/api/whatsapp')
            ]"/>
        </div>

        <div>
            <x-input-label for="url" :value="__('Url')" />
            <x-text-input id="url" name="url" type="text" class="mt-1 block w-full" required autofocus autocomplete="url" />
            <x-input-error class="mt-2" :messages="$errors->get('url')" />
        </div>

        <div>
            <x-input-label for="token" :value="__('token')" />
            <x-text-input id="token" name="token" type="text" class="mt-1 block w-full" :value="old('token')" required autocomplete="token" />
            <x-input-error class="mt-2" :messages="$errors->get('token')" />
        </div>

        <div class="flex items-center gap-4">
            <x-primary-button>{{ __('Save') }}</x-primary-button>
        </div>

        @if (session('ok') === true)
            <x-small-notification class="bg-green-500" :title="session('description')"/>
        @elseif (session('ok') === false)
            <x-small-notification class="bg-red-500" :title="session('description')"/>
        @endif
        
    </form>
</section>
