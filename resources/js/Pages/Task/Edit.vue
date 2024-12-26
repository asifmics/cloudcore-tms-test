<script setup>
import AuthenticatedLayout from "@/Layouts/AuthenticatedLayout.vue";
import InputLabel from "@/Components/InputLabel.vue";
import TextInput from "@/Components/TextInput.vue";
import InputError from "@/Components/InputError.vue";
import PrimaryButton from "@/Components/PrimaryButton.vue";
import 'vue-toast-notification/dist/theme-sugar.css';
import {useToast} from 'vue-toast-notification';

const $toast = useToast();

import {Head, useForm} from "@inertiajs/vue3";

const props = defineProps({
    task: Array,
    completed: String,
    pending: String,
    progress: String,
})

const form = useForm({
    _method: 'patch',
    title: props.task.title,
    status: props.task.status,
    description: props.task.description,
})

const submit = () => {
    form.submit('post', route("tasks.update", props.task.id), {
        onSuccess: () => {
            $toast.success("Task updated successfully!");
        },
        onError: () => {
            $toast.error("Failed to updated task!");
        }
    })
}
</script>

<template>
    <Head>
        <title>Tasks</title>
    </Head>
    <AuthenticatedLayout>

        <div class="grid lg:grid-cols-1 md:grid-cols-2 grid-cols-1 gap-4 px-5 pt-5 max-h-96">
            <div class="col-span-2 border rounded-xl shadow-xl max-h-96">
                <div class="w-full mx-auto">
                    <div class="p-4 sm:p-8 bg-white rounded-xl shadow sm:rounded-lg">
                        <h2 class="p-0 text-secondary text-20 font-medium">Update task</h2>
                        <form @submit.prevent="submit" class="mt-6 space-y-6">
                            <div class="grid grid-cols-3 w-full gap-8">
                                <div
                                    class="col-span-2 grid grid-cols-2 gap-4 p-5 border-gray-100 border rounded-2xl shadow-sm">
                                    <div class="w-full">
                                        <InputLabel for="title" value="Task title" icon="*"/>

                                        <TextInput
                                            id="title"
                                            type="text"
                                            placeholder="ex: Shirt"
                                            class="mt-1 block w-full"
                                            v-model="form.title"/>

                                        <InputError class="mt-2" :message="form.errors.title"/>

                                    </div>

                                    <div class="w-full">
                                        <InputLabel for="text" value="Task description"/>

                                        <TextInput
                                            type="text"
                                            placeholder="ex: task description"
                                            class="mt-1 block w-full"
                                            v-model="form.description"/>

                                        <InputError class="mt-2" :message="form.errors.description"/>

                                    </div>

                                    <div class="grid grid-cols-2 w-full gap-4 col-span-1 justify-center">
                                        <div class="w-full">
                                            <InputLabel value="Status" icon="*"/>
                                            <div class="flex flex-row gap-4 pt-2">
                                                <input
                                                    type="radio"
                                                    class="mt-1"
                                                    v-model="form.status"
                                                    :class="form.status === props.pending ? '!accent-green' : ''"
                                                    :value=props.pending
                                                />
                                                <label :class="form.status === props.pending ? 'text-sky-500' : 'text-gray-700'"
                                                       class="font-semibold text-md  self-end capitalize">{{ props.pending }}</label>

                                                <input
                                                    type="radio"
                                                    class="mt-1"
                                                    v-model="form.status"
                                                    :value=props.progress
                                                    :class="form.status === props.progress  ? '!accent-danger' : ''"
                                                />
                                                <label :class="form.status === props.progress ? 'text-yellow-600' : 'text-gray-700'"
                                                       class="font-semibold text-md  self-end capitalize">{{ props.progress }}</label>

                                                <input
                                                    type="radio"
                                                    class="mt-1"
                                                    v-model="form.status"
                                                    :value=props.completed
                                                    :class="form.status === props.completed  ? '!accent-danger' : ''"
                                                />
                                                <label :class="form.status === props.completed ? 'text-lime-600' : 'text-gray-700'"
                                                       class="font-semibold text-md  self-end capitalize">{{ props.completed }}</label>


                                            </div>

                                            <InputError class="mt-2" :message="form.errors.status"/>
                                        </div>

                                    </div>
                                </div>

                            </div>


                            <div class="flex items-center gap-4 justify-end">
                                <PrimaryButton>
                                    <span class="pl-2">update task</span>
                                </PrimaryButton>

                                <Transition
                                    enter-active-class="transition ease-in-out"
                                    enter-from-class="opacity-0"
                                    leave-active-class="transition ease-in-out"
                                    leave-to-class="opacity-0"
                                >
                                </Transition>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>

    </AuthenticatedLayout>
</template>

<style scoped>

</style>
