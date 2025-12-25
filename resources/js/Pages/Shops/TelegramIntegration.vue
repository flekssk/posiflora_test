<template>
    <AppLayout :title="`Telegram интеграции — ${shop.name}`">
        <template #header>
            <h2 class="font-semibold text-xl text-gray-800 dark:text-gray-200">
                Telegram-интеграции
            </h2>
        </template>

        <div class="py-10 max-w-4xl mx-auto space-y-8">

            <div class="bg-white dark:bg-gray-800 shadow rounded">
                <table class="w-full text-sm">
                    <thead class="border-b">
                    <tr>
                        <th class="p-3 text-left">Chat ID</th>
                        <th class="p-3 text-left">Создано</th>
                        <th class="p-3"></th>
                    </tr>
                    </thead>

                    <tbody>
                    <tr v-for="i in integrations" :key="i.id"
                        class="border-b last:border-0">
                        <td class="p-3">{{ i.chat_id }}</td>
                        <td class="p-3 text-gray-500">{{ i.created_at }}</td>
                        <td class="p-3 text-right">
                            <button
                                class="text-red-600 hover:underline"
                                @click="$inertia.delete(
                                        route('shops.telegram.destroy', [shop.id, i.id])
                                    )"
                            >
                                Удалить
                            </button>
                        </td>
                    </tr>

                    <tr v-if="integrations.length === 0">
                        <td colspan="3" class="p-4 text-center text-gray-500">
                            Интеграций пока нет
                        </td>
                    </tr>
                    </tbody>
                </table>
            </div>

            <form @submit.prevent="submit"
                  class="bg-white dark:bg-gray-800 shadow rounded p-6 space-y-4">

                <h3 class="font-semibold">Добавить интеграцию</h3>

                <div>
                    <label class="block text-sm font-medium">Bot Token</label>
                    <input
                        v-model="form.botToken"
                        type="text"
                        class="mt-1 w-full rounded border-gray-300"
                    />
                </div>

                <div>
                    <label class="block text-sm font-medium">Chat ID</label>
                    <input
                        v-model="form.chatId"
                        type="text"
                        class="mt-1 w-full rounded border-gray-300"
                    />
                    <p class="text-xs text-gray-500 mt-1">
                        Узнать chat_id можно через @userinfobot
                    </p>
                </div>

                <button
                    type="submit"
                    class="bg-indigo-600 text-white px-4 py-2 rounded"
                    :disabled="form.processing"
                >
                    Добавить
                </button>
            </form>

        </div>
    </AppLayout>
</template>

<script setup>
import { useForm } from '@inertiajs/vue3'
import AppLayout from '@/Layouts/AppLayout.vue'

const props = defineProps({
    shop: Object,
    integrations: Array,
})

const form = useForm({
    botToken: '',
    chatId: '',
})

function submit() {
    form.post(`api/v1/shops/${props.shop.id}/telegram/connect`, {
        onSuccess: () => form.reset(),
    })
}
</script>

