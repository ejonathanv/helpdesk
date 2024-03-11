<template>
    <Head title="Notificaciones" />
    <AuthenticatedLayout>
        <template #header>
            <h2 class="font-semibold text-xl text-gray-800 leading-tight">Notificaciones</h2>
        </template>
        <div class="container py-7">
            <div class="w-9/12 mx-auto">
                <div class="card">
                    <div class="flex items-start justify-between mb-5">
                        <h2 class="font-semibold text-gray-800 leading-tight">Notificaciones</h2>
                        <form @submit.prevent="deleteReadNotifications">
                            <PrimaryButton type="submit" class="mb-4 flex items-center space-x-2">
                                <i class="fas fa-fw fa-trash-alt"></i>
                                <span>Eliminar leídas</span>
                            </PrimaryButton>
                        </form>
                    </div>
                    <ul v-if="notifications.length">
                        <li v-for="notification in notifications" :key="notification.id"
                            class="py-4 border-b border-gray-200 last:border-b-0"
                            :class="{
                                'opacity-50': notification.read_at,
                            }">
                            <div class="flex items-start justify-between">
                                <div class="w-full space-y-2">
                                    <a :href="'/dashboard/notifications/' + notification.id">
                                        <h3 class="font-semibold text-lg text-gray-800 leading-tight">
                                            {{ notification.data.subject }}
                                            <span v-if="notification.read_at" class="text-sm">
                                                <i class="fas fa-fw fa-check text-green-500"></i>
                                            </span>
                                            <span v-else class="text-sm">
                                                (Sin leer)
                                            </span>
                                        </h3>
                                    </a>
                                    <p class="text-gray-500">
                                        {{ notification.data.message }}
                                    </p>
                                </div>
                                <div>
                                    <p class="text-gray-600 whitespace-nowrap text-sm font-bold">
                                        {{ notification.data.date }}
                                    </p>
                                </div>
                            </div>
                        </li>
                    </ul>
                    <div v-else class="text-center text-gray-600">
                        No hay notificaciones.
                    </div>
                </div>
            </div>
        </div>
    </AuthenticatedLayout>
</template>
<script>
import AuthenticatedLayout from '@/Layouts/AuthenticatedLayout.vue';
import { Head } from '@inertiajs/vue3';
import PrimaryButton from '@/Components/PrimaryButton.vue';
export default {
    name: 'NotificationsIndex',
    props: {
        notifications: {
            type: Array,
            required: true,
        },
    },
    components: {
        AuthenticatedLayout,
        Head,
        PrimaryButton,
    },
    data() {
        return {};
    },
    methods: {
        deleteReadNotifications() {
            const route = '/dashboard/notifications/delete-all-read';
            this.$inertia.post(route, {}, {
                preserveScroll: true,
            });
        },
    }
}
</script>