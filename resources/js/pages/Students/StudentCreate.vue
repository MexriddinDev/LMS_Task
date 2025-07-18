<template>
    <Head title="Tarbiyalanuvchi qo‘shish" />

    <AppLayout :breadcrumbs="breadcrumbs">
        <div class="p-4 space-y-4 text-gray-800 dark:text-gray-200 max-w-4xl mx-auto">
            <div class="flex items-center gap-4">
                <Link :href="route('students.index')" class="text-gray-500 hover:text-gray-700 dark:hover:text-gray-400">
                    <i class="fas fa-arrow-left text-xl"></i>
                </Link>
                <h1 class="text-2xl font-bold">O'quvchi qo‘shish</h1>
            </div>

            <p class="text-gray-600 dark:text-gray-400">Yangi o'quvchi hisobi yarating</p>

            <form @submit.prevent="submitForm" class="space-y-6">
                <div class="p-6 rounded-xl border border-gray-200 dark:border-gray-700 shadow-sm space-y-4">
                    <h2 class="text-lg font-semibold text-gray-800 dark:text-gray-100">O'quvchi ma'lumotlari</h2>

                    <div class="flex items-center gap-6">
                        <div class="w-24 h-24 rounded-full border border-gray-300 dark:border-gray-600 flex items-center justify-center overflow-hidden">
                            <img v-if="form.profile_picture_preview" :src="form.profile_picture_preview" alt="Profil rasmi" class="w-full h-full object-cover">
                            <i v-else class="fas fa-user text-gray-400 dark:text-gray-500 text-4xl"></i>
                        </div>
                        <div>
                            <input type="file" @change="handleProfilePictureUpload" class="hidden" ref="profilePictureInput">
                            <button type="button" @click="openProfilePictureInput" class="text-black px-4 py-2 rounded-lg border border-gray-300 hover:bg-gray-100 dark:text-gray-200 dark:border-gray-600 dark:hover:bg-gray-600 transition-colors duration-200">
                                Rasm tanlash
                            </button>
                            <p class="text-gray-500 dark:text-gray-400 text-sm mt-1">Profil rasmini yuklang (ixtiyoriy). JPG, PNG yoki GIF. Maksimal 2 MB.</p>
                            <p v-if="form.errors.profile_picture" class="text-red-500 text-sm mt-1">{{ form.errors.profile_picture }}</p>
                        </div>
                    </div>
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                        <div>
                            <label for="full_name" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">To'liq ism</label>
                            <input
                                type="text"
                                id="full_name"
                                v-model="form.full_name"
                                class="w-full px-3 py-2 border border-gray-300 dark:border-gray-600 rounded-lg focus:ring-blue-500 focus:border-blue-500 dark:text-gray-200 placeholder-gray-400 dark:placeholder-gray-500"
                                placeholder="To'liq ism kiriting"
                                required
                            >
                            <p v-if="form.errors.full_name" class="text-red-500 text-sm mt-1">{{ form.errors.full_name }}</p>
                        </div>

                        <div>
                            <label for="phone_number" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">Telefon raqami</label>
                            <input
                                type="text"
                                inputmode="numeric"
                                pattern="[0-9]*"
                                id="phone_number"
                                v-model="form.phone"
                                class="w-full px-3 py-2 border border-gray-300 dark:border-gray-600 rounded-lg focus:ring-blue-500 focus:border-blue-500 dark:text-gray-200 placeholder-gray-400 dark:placeholder-gray-500"
                                placeholder="XX XXX XX XX"
                                required
                                maxlength="9"
                                @input="validatePhoneInput"
                            >
                            <p v-if="form.errors.phone" class="text-red-500 text-sm mt-1">{{ form.errors.phone }}</p>
                            <p v-if="clientPhoneError" class="text-red-500 text-sm mt-1">{{ clientPhoneError }}</p>
                        </div>

                        <div>
                            <label for="birth_date" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">Tug'ilgan sana</label>
                            <input
                                type="date"
                                id="birth_date"
                                v-model="form.birth_date"
                                class="w-full px-3 py-2 border border-gray-300 dark:border-gray-600 rounded-lg focus:ring-blue-500 focus:border-blue-500 dark:text-gray-200 placeholder-gray-400 dark:placeholder-gray-500"
                                required
                            >
                            <p v-if="form.errors.birth_date" class="text-red-500 text-sm mt-1">{{ form.errors.birth_date }}</p>
                        </div>

                        <div>
                            <label for="balance" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">Balans</label>
                            <input
                                type="number"
                                id="balance"
                                v-model.number="form.balance"
                                class="w-full px-3 py-2 border border-gray-300 dark:border-gray-600 rounded-lg focus:ring-blue-500 focus:border-blue-500 dark:text-gray-200 placeholder-gray-400 dark:placeholder-gray-500"
                                placeholder="0"
                                required
                                min="0"
                            >
                            <p v-if="form.errors.balance" class="text-red-500 text-sm mt-1">{{ form.errors.balance }}</p>
                        </div>

                        <div>
                            <label for="group" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">Guruhni tanlang</label>
                            <select
                                id="group"
                                v-model="form.group_id"
                                class="w-full px-3 py-2 border border-gray-300 dark:border-gray-600 rounded-lg focus:ring-blue-500 focus:border-blue-500 dark:text-gray-200 bg-white dark:bg-gray-950"
                                :required="groups.length > 0"
                            >
                                <option value="">--- Guruhni tanlang ---</option>
                                <option v-for="group in groups" :key="group.id" :value="group.id">
                                    {{ group.name }}
                                </option>
                            </select>
                            <p v-if="form.errors.group_id" class="text-red-500 text-sm mt-1">{{ form.errors.group_id }}</p>
                            <p v-if="groups.length === 0" class="text-gray-500 dark:text-gray-400 text-sm mt-1">
                                Guruhlar mavjud emas. Avval guruh yaratishingiz kerak.
                                <Link :href="route('groups.create')" class="text-blue-500 hover:underline">Guruh yaratish</Link>
                            </p>
                        </div>
                    </div>
                </div>

                <div class="flex justify-end gap-3">
                    <Link :href="route('students.index')" class="text-gray-700 px-5 py-2 rounded-lg border border-gray-300 hover:bg-gray-100 dark:text-gray-200 dark:border-gray-600 dark:hover:bg-gray-600 transition-colors duration-200">
                        Bekor qilish
                    </Link>
                    <button type="submit" :disabled="form.processing" class="bg-white text-black px-5 py-2 rounded-lg hover:bg-gray-100 dark:bg-white dark:text-black dark:hover:bg-gray-200 transition-colors duration-200">
                        <span v-if="form.processing">Yaratilmoqda...</span>
                        <span v-else>O'quvchi yaratish</span>
                    </button>
                </div>
            </form>
        </div>
    </AppLayout>
</template>

<script setup lang="ts">
import AppLayout from '@/layouts/AppLayout.vue'
import { Head, Link, useForm } from '@inertiajs/vue3';
import { ref } from 'vue';

// Define props to receive the list of groups
const props = defineProps<{
    groups: {
        id: number;
        name: string;
    }[];
}>();

const form = useForm({
    full_name: '',
    phone: '',
    birth_date: '' as string,
    balance: 0 as number,
    profile_picture: null as File | null,
    profile_picture_preview: null as string | null,
    group_id: null as number | null, // New field for group_id
});

const clientPhoneError = ref<string | null>(null);
const profilePictureInput = ref<HTMLInputElement | null>(null);

const openProfilePictureInput = () => {
    profilePictureInput.value?.click();
};

const handleProfilePictureUpload = (event: Event) => {
    const target = event.target as HTMLInputElement;
    const file = target.files?.[0];
    if (file) {
        form.profile_picture = file;
        const reader = new FileReader();
        reader.onload = (e) => {
            form.profile_picture_preview = e.target?.result as string;
        };
        reader.readAsDataURL(file);
    } else {
        form.profile_picture = null;
        form.profile_picture_preview = null;
    }
};

const validatePhoneInput = () => {
    let value = form.phone ? String(form.phone) : '';
    value = value.replace(/\D/g, '');
    value = value.slice(0, 9);
    form.phone = value;

    clientPhoneError.value = null;

    if (value.length === 0) {
        clientPhoneError.value = "Telefon raqami kiritilishi shart.";
    } else if (value.length !== 9) {
        clientPhoneError.value = "Telefon raqami 9 ta raqamdan iborat bo'lishi shart.";
    }
};

const submitForm = () => {
    validatePhoneInput();

    // Check for client-side phone error before submission
    if (clientPhoneError.value) {
        return;
    }

    // Explicitly set group_id to null if the "--- Guruhni tanlang ---" option is selected
    if (form.group_id === '') { // Select option value is empty string, so check for that
        form.group_id = null;
    }

    // Clear Inertia's phone error if client-side validation passes (to avoid confusion)
    if (form.errors.phone) {
        form.errors.phone = undefined;
    }

    form.post(route('students.store'), {
        onSuccess: () => {
            form.reset();
            clientPhoneError.value = null;
            // Optionally redirect or show success message
            // router.visit(route('students.index'));
        },
        onError: (errors) => {
            console.error('Oquvchi yaratishda xato:', errors);
        },
    });
};

const breadcrumbs = [
    { title: 'Dashboard', href: '/dashboard' },
    { title: 'Oquvchi', href: '/students' },
    { title: 'Oquvchi qo‘shish', href: '/students/create' } // Corrected breadcrumb link
];
</script>

<style scoped>
@import url('https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css');
</style>
