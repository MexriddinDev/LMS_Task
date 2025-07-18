<template>
    <Head title="Guruhlar" />

    <AppLayout :breadcrumbs="breadcrumbs">
        <div class="flex justify-between items-center mb-4 p-4">
            <h1 class="text-xl font-bold">Guruhlar ro'yxati</h1>
            <Link
                href="/groups/create"
                class="bg-blue-600 text-white px-4 py-2 rounded hover:bg-blue-700"
            >
                + Guruh qo'shish
            </Link>
        </div>

        <div class="overflow-x-auto px-4">
            <table class="min-w-full border rounded">
                <thead class="bg-gray-100 dark:bg-gray-800">
                <tr>
                    <th class="p-2 border">#</th>
                    <th class="p-2 border text-left">Nomi</th>
                    <th class="p-2 border text-left">O'qituvchi</th>
                    <th class="p-2 border text-left">A'zolar</th>
                    <th class="p-2 border">Amallar</th>
                </tr>
                </thead>
                <tbody>
                <tr
                    v-for="(group, index) in groups"
                    :key="group.id"
                    class="hover:bg-gray-50 dark:hover:bg-gray-700"
                >
                    <td class="p-2 border text-center">{{ index + 1 }}</td>
                    <td class="p-2 border">{{ group.name }}</td>
                    <td class="p-2 border">{{ group.teacher.full_name }}</td>
                    <td class="p-2 border">{{ group.students_count }}</td>
                    <td class="p-2 border text-center space-x-2">
                        <Link
                            :href="`/groups/${group.id}/edit`"
                            class="text-blue-600 hover:underline"
                        >
                            Tahrirlash
                        </Link>
                        <Link
                            :href="`/groups/${group.id}`"
                            method="delete"
                            as="button"
                            class="text-red-600 hover:underline"
                        >
                            O‘chirish
                        </Link>
                    </td>
                </tr>
                </tbody>
            </table>
        </div>
    </AppLayout>
</template>

<script setup lang="ts">
import AppLayout from '@/layouts/AppLayout.vue'
import { Head, Link } from '@inertiajs/vue3'

interface Teacher {
    id: number
    full_name: string
}

interface Group {
    id: number
    name: string
    teacher: Teacher
    students_count: number
}

defineProps<{
    groups: Group[]
}>()

const breadcrumbs = [
    { title: 'Dashboard', href: '/dashboard' },
    { title: 'Guruhlar', href: '/groups' }
]
</script>
