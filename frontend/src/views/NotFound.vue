<template>
    <div class="not-found">
        <div class="content">
            <h1>404</h1>
            <h2>Página não encontrada</h2>
            <p>A página que você está procurando não existe ou foi removida.</p>
            <button @click="goBack" class="back-button">Voltar</button>
        </div>
    </div>
</template>

<script setup>
import { useRouter } from 'vue-router'

const router = useRouter()

const goBack = () => {
    router.go(-1)
}
</script>

<style scoped>
.not-found {
    height: 100vh;
    display: flex;
    justify-content: center;
    align-items: center;
    text-align: center;
}

.content {
    padding: 2rem;
}

h1 {
    font-size: 6rem;
    margin: 0;
    color: #e74c3c;
}

h2 {
    font-size: 2rem;
    margin: 1rem 0;
    color: #2c3e50;
}

p {
    color: #7f8c8d;
    margin-bottom: 2rem;
}

.back-button {
    padding: 0.8rem 1.5rem;
    background-color: #3498db;
    color: white;
    border: none;
    border-radius: 4px;
    cursor: pointer;
    font-size: 1rem;
    transition: background-color 0.3s;
}

.back-button:hover {
    background-color: #2980b9;
}
</style>