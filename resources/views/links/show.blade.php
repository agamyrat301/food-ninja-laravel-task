<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('Статистика перехода') }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8 space-y-6">

            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg p-6">
                <dl class="grid grid-cols-1 sm:grid-cols-3 gap-4">
                    <div>
                        <dt class="text-sm text-gray-500">{{ __('Короткая ссылка') }}</dt>
                        <dd class="mt-1">
                            <a href="{{ $link->short_url }}" target="_blank" class="text-indigo-600 hover:underline">{{ $link->short_url }}</a>
                        </dd>
                    </div>
                    <div>
                        <dt class="text-sm text-gray-500">{{ __('Оригинальный URL') }}</dt>
                        <dd class="mt-1 break-all">{{ $link->original_url }}</dd>
                    </div>
                    <div>
                        <dt class="text-sm text-gray-500">{{ __('Всего переходов') }}</dt>
                        <dd class="mt-1 text-2xl font-semibold">{{ $link->clicks()->count() }}</dd>
                    </div>
                </dl>

                <a href="{{ route('links.index') }}" class="inline-block mt-6 text-sm text-gray-500 hover:underline">&larr; {{ __('Назад к списку ссылок') }}</a>
            </div>

            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                <div class="overflow-x-auto">
                    <table class="min-w-full divide-y divide-gray-200">
                        <thead class="bg-gray-50">
                            <tr>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">{{ __('IP-адрес') }}</th>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">{{ __('Дата и время') }}</th>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">{{ __('User agent') }}</th>
                            </tr>
                        </thead>
                        <tbody class="bg-white divide-y divide-gray-200">
                            @forelse ($clicks as $click)
                                <tr>
                                    <td class="px-6 py-4 whitespace-nowrap">{{ $click->ip_address }}</td>
                                    <td class="px-6 py-4 whitespace-nowrap">{{ $click->created_at->format('d.m.Y H:i:s') }}</td>
                                    <td class="px-6 py-4 max-w-md truncate text-gray-500" title="{{ $click->user_agent }}">{{ $click->user_agent }}</td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="3" class="px-6 py-4 text-center text-gray-500">
                                        {{ __('Переходов пока не было.') }}
                                    </td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
                <div class="p-4">
                    {{ $clicks->links() }}
                </div>
            </div>
        </div>
    </div>
</x-app-layout>
