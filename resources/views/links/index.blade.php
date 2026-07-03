<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('Мои ссылки') }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8 space-y-6">

            @if (session('status'))
                <div class="bg-green-100 border border-green-300 text-green-800 px-4 py-3 rounded-lg">
                    {{ session('status') }}
                </div>
            @endif

            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg p-6">
                <h3 class="text-lg font-medium text-gray-900 mb-4">{{ __('Создать короткую ссылку') }}</h3>

                <form method="POST" action="{{ route('links.store') }}" class="flex flex-col sm:flex-row gap-4">
                    @csrf
                    <div class="flex-1">
                        <x-text-input
                            type="url"
                            name="original_url"
                            :value="old('original_url')"
                            placeholder="https://example.com/page"
                            class="w-full"
                            required
                            autofocus
                        />
                        <x-input-error :messages="$errors->get('original_url')" class="mt-2" />
                    </div>
                    <x-primary-button type="submit">{{ __('Сократить') }}</x-primary-button>
                </form>
            </div>

            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                <div class="overflow-x-auto">
                    <table class="min-w-full divide-y divide-gray-200">
                        <thead class="bg-gray-50">
                            <tr>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">{{ __('Короткая ссылка') }}</th>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">{{ __('Оригинальный URL') }}</th>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">{{ __('Переходы') }}</th>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">{{ __('Создана') }}</th>
                                <th class="px-6 py-3"></th>
                            </tr>
                        </thead>
                        <tbody class="bg-white divide-y divide-gray-200">
                            @forelse ($links as $link)
                                <tr>
                                    <td class="px-6 py-4 whitespace-nowrap">
                                        <a href="{{ $link->short_url }}" target="_blank" class="text-indigo-600 hover:underline">
                                            {{ $link->short_url }}
                                        </a>
                                    </td>
                                    <td class="px-6 py-4 max-w-xs truncate">
                                        <span class="text-gray-600" title="{{ $link->original_url }}">{{ $link->original_url }}</span>
                                    </td>
                                    <td class="px-6 py-4 whitespace-nowrap text-gray-900">
                                        {{ $link->clicks_count }}
                                    </td>
                                    <td class="px-6 py-4 whitespace-nowrap text-gray-500">
                                        {{ $link->created_at->format('d.m.Y H:i') }}
                                    </td>
                                    <td class="px-6 py-4 whitespace-nowrap text-right space-x-3">
                                        <a href="{{ route('links.show', $link) }}" class="text-indigo-600 hover:underline">{{ __('Статистика') }}</a>
                                        <form method="POST" action="{{ route('links.destroy', $link) }}" class="inline" onsubmit="return confirm('{{ __('Удалить ссылку?') }}')">
                                            @csrf
                                            @method('DELETE')
                                            <button type="submit" class="text-red-600 hover:underline">{{ __('Удалить') }}</button>
                                        </form>
                                    </td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="5" class="px-6 py-4 text-center text-gray-500">
                                        {{ __('У вас пока нет ссылок.') }}
                                    </td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>
