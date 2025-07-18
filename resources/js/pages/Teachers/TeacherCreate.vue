<template>
    <Head title="Xodim qo‘shish" />

    <AppLayout :breadcrumbs="breadcrumbs">
        <div class="p-4 space-y-4 text-gray-800 dark:text-gray-200 max-w-4xl mx-auto">
            <div class="flex items-center gap-4">
                <Link :href="route('teachers.index')" class="text-gray-500 hover:text-gray-700 dark:hover:text-gray-400">
                    <i class="fas fa-arrow-left text-xl"></i>
                </Link>
                <h1 class="text-2xl font-bold">Xodim qo‘shish</h1>
            </div>

            <p class="text-gray-600 dark:text-gray-400">Yangi xodim hisobi yarating</p>

            <form @submit.prevent="submitForm" class="space-y-6">
                <div class="p-6 rounded-xl border border-gray-200 dark:border-gray-700 shadow-sm space-y-4">
                    <h2 class="text-lg font-semibold text-gray-800 dark:text-gray-100">Xodim ma'lumotlari</h2>

                    <div class="flex items-center gap-6">
                        <div class="w-24 h-24 rounded-full border border-gray-300 dark:border-gray-600 flex items-center justify-center overflow-hidden">
                            <img v-if="form.profile_picture_preview" :src="form.profile_picture_preview" alt="Profile Preview" class="w-full h-full object-cover">
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
                                v-model="form.name"
                                class="w-full px-3 py-2 border border-gray-300 dark:border-gray-600 rounded-lg focus:ring-blue-500 focus:border-blue-500 dark:text-gray-200 placeholder-gray-400 dark:placeholder-gray-500"
                                placeholder="To'liq ism kiriting"
                                required
                            >
                            <p v-if="form.errors.name" class="text-red-500 text-sm mt-1">{{ form.errors.name }}</p>
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
                            <label for="password" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">Parol</label>
                            <input
                                type="password"
                                id="password"
                                v-model="form.password"
                                class="w-full px-3 py-2 border border-gray-300 dark:border-gray-600 rounded-lg focus:ring-blue-500 focus:border-blue-500 dark:text-gray-200 placeholder-gray-400 dark:placeholder-gray-500"
                                placeholder="Parolingizni kiriting"
                                required
                            >
                            <p v-if="form.errors.password" class="text-red-500 text-sm mt-1">{{ form.errors.password }}</p>
                        </div>

                        <div>
                            <label for="password_confirmation" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">Parolni tasdiqlash</label>
                            <input
                                type="password"
                                id="password_confirmation"
                                v-model="form.password_confirmation"
                                class="w-full px-3 py-2 border border-gray-300 dark:border-gray-600 rounded-lg focus:ring-blue-500 focus:border-blue-500 dark:text-gray-200 placeholder-gray-400 dark:placeholder-gray-500"
                                placeholder="Parolni tasdiqlang"
                                required
                            >
                            <p v-if="form.errors.password_confirmation" class="text-red-500 text-sm mt-1">{{ form.errors.password_confirmation }}</p>
                        </div>
                    </div>
                </div>

                <div class="flex justify-end gap-3">
                    <Link :href="route('teachers.index')" class="text-gray-700 px-5 py-2 rounded-lg border border-gray-300 hover:bg-gray-100 dark:text-gray-200 dark:border-gray-600 dark:hover:bg-gray-600 transition-colors duration-200">
                        Bekor qilish
                    </Link>
                    <Button type="submit" :disabled="form.processing" class="bg-white text-black px-5 py-2 rounded-lg hover:bg-gray-100 dark:bg-white dark:text-black dark:hover:bg-gray-200 transition-colors duration-200">
                        <span v-if="form.processing">Yaratilmoqda...</span>
                        <span v-else>Xodim yaratish</span>
                    </Button>
                </div>
            </form>
        </div>
    </AppLayout>
</template>

<script setup lang="ts">
import AppLayout from '@/layouts/AppLayout.vue'
import { Head, Link, useForm } from '@inertiajs/vue3';
import { ref } from 'vue';
// import Button from '@/Components/Button.vue';

const form = useForm({
    name: '',
    phone: '',
    password: '',
    password_confirmation: '',
    profile_picture: null as File | null,
    profile_picture_preview: null as string | null,
});

const profilePictureInput = ref<HTMLInputElement | null>(null);
const clientPhoneError = ref<string | null>(null);

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

    if (clientPhoneError.value) {
        return;
    }

    if (form.errors.phone) {
        form.errors.phone = undefined;
    }

    console.log('Form submitted:', form.data());
    form.post(route('teachers.store'), {
        onSuccess: () => {
            console.log('Teacher created successfully!');
            form.reset();
            clientPhoneError.value = null;
        },
        onError: (errors) => {
            console.error('Error creating teacher:', errors);
        },
    });
};

const breadcrumbs = [
    { title: 'Dashboard', href: '/dashboard' },
    { title: 'Xodimlar', href: '/teachers' },
    { title: 'Xodim qo‘shish', href: '/teachers/create' }
];
</script>

<style scoped>
@import url('https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css');
</style>
