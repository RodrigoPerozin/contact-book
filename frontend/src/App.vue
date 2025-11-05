<script setup lang="ts">

  import { ref, onMounted, computed } from 'vue'
  import * as bootstrap from 'bootstrap'
  import VueSelect from "vue3-select-component";
  // @ts-ignore: no type declarations for the package style entry
  import "vue3-select-component/styles";
  import Swal from 'sweetalert2';

  interface Country {
    nome: string
    fone: string
    iso: string
  }

  export interface Contact {
  id: number;
  name: string;
  email: string;
  phone: string | null;
  cep: number | string | null;
  city: string | null;
  country: string | null;
  state: string | null;
  neighborhood: string | null;
  street_address: string | null;
  house_number: number | string | null;
  complement: string | null;
  picture: string | null;
  created_at: string;
  updated_at: string;
}

// 🔗 Estrutura dos links de paginação
export interface PaginationLink {
  url: string | null;
  label: string;
  page: number | null;
  active: boolean;
}

// 📄 Estrutura completa retornada pela API Laravel
export interface Paginator {
  current_page: number;
  data: Contact[];
  first_page_url: string;
  from: number | null;
  last_page: number;
  last_page_url: string;
  links: PaginationLink[];
  next_page_url: string | null;
  path: string;
  per_page: number;
  prev_page_url: string | null;
  to: number | null;
  total: number;
}

class ACTION {
  static create = 'create';
  static edit ='edit';
}

  //----------CREATE/UPDATE FORM-------//
  var modal: bootstrap.Modal
  const formAction = ref('create')
  const isMenuOpen = ref<boolean>(false)
  const country = ref<string|null>(null)
  const name = ref<string|null>('')
  const email = ref<string|null>('')
  const phone = ref<string|null>('')
  const cep = ref<string|null>('')
  const city = ref<string|null>('')
  const neighborhood = ref<string|null>('')
  const street_address = ref<string|null>('')
  const house_number = ref<string|null>('')
  const complement = ref<string|null>('')
  const picture = ref<string|null>(null)
  const state = ref<string|null>('')
  const countries = ref<Country[]>([])
  //-----------------------------------//

  const defaultPicture = ref<string | null>(null);
  var paginator = ref<Paginator|null>(null)

  async function loadDefaultPicture() {
  try {
    const response = await fetch('http://localhost:8000/api/contact/default-picture', {
      method: 'GET',
      headers: {
        "Accept": "application/json",
        "Content-Type": "application/json"
      }
    });

    if (!response.ok) {
      console.error(`Erro ao buscar imagem: ${response.status}`);
    }

    // supondo que o backend retorne algo como { "image": "data:image/png;base64,..." }
    const data = await response.json();
    defaultPicture.value = data.image; // salva a string base64
    console.log(defaultPicture)
  } catch (error) {
    console.error('Erro ao buscar imagem:', error);
  }
}

  async function loadContacts() {
    var response = await fetch('http://localhost:8000/api/contact', {
      method: 'GET',
      headers: {
        "Accept": "application/json",
        "Content-Type": "application/json"
      }
    })  
    paginator.value = await response.json();
    console.log(paginator.value)
  }

  function cleanFormData(){
    country.value = null
    name.value = null
    cep.value = null
    city.value = null
    neighborhood.value = null
    street_address.value = null
    house_number.value = null
    complement.value = null
    picture.value = null
    state.value = null
    countries.value = []
    email.value = null
    phone.value = null
  }
  
  const create = async() => {
    const payload = {
      name: name.value,
      email: email.value,
      phone: phone.value ?? "",
      cep: String(cep.value) ?? "",
      country: country.value ?? "",
      city: city.value ?? "",
      neighborhood: neighborhood.value ?? "",
      street_address: street_address.value ?? "",
      house_number: house_number.value ?? "",
      complement: complement.value ?? "",
      picture: picture.value ?? null,
      state: state.value ?? ""
    };

    const response = await fetch('http://localhost:8000/api/contact', {
      method: 'POST',
      headers: {
        "Accept": "application/json",
        "Content-Type": "application/json"
      },
      body: JSON.stringify(payload)
    })
    
    const contentType = response.headers.get("content-type");
    let data = null;

    // tenta converter o corpo em JSON se for JSON mesmo
    if (contentType && contentType.includes("application/json")) {
      data = await response.json();
    } 
  
    if (!response.ok) {
      // se o status for erro (ex: 400, 404, 500)
      let msg = data.message;

      if(data.error.includes("email") && data.error.includes("taken")){
        msg = "O email informado já está cadastrado.";
      }else if(data.error.includes("name") && data.error.includes("required")){
        msg = "O nome é obrigatório.";
      }else if(data.error.includes("email") && data.error.includes("required")){
        msg = "O email é obrigatório.";
      }

      showAlert('Erro!', msg, 'error');
      return
    }

    showAlert('Sucesso!', data.message, 'success');
    loadContacts()
    fecharModal();  
  }  

  function showAlert(title:string, message:string, icon:'success' | 'error' | 'warning' | 'info' | 'question') {
    Swal.fire({
      title: title,
      text: message,
      icon: icon,
      toast: true,
      position: 'top-end', 
      theme: 'dark',
      timer: 3000,
      timerProgressBar: true,
      showConfirmButton: false,
      background: '#2c2c2c',
      color: '#ffffff'
    });
  }

  async function onFileChange(event: Event) {
    const target = event.target as HTMLInputElement;
    if (target.files && target.files.length > 0) {
      let file = target.files[0] ?? null;
      if(file !== null) {
        picture.value = await fileToBase64(file);
      }
    } else {
      picture.value = null;
    }
  }

  function fileToBase64(file: File): Promise<string> {
    return new Promise((resolve, reject) => {
      const reader = new FileReader();

      reader.onload = () => {
        resolve(reader.result as string); // reader.result é do tipo string | ArrayBuffer
      };

      reader.onerror = (error) => reject(error);

      reader.readAsDataURL(file); // converte o arquivo para base64
    });
  }

  function openModal(action: ACTION, id?: number) {
    switch (action) {
      case ACTION.create:
        cleanFormData()
        formAction.value = 'create'
        break
      case ACTION.edit:
        formAction.value = 'edit'
        break
    }

    if(id !== undefined && !isNaN(id)){
      let contact = paginator.value?.data.filter(contact => contact.id === id)[0];
      if (contact !== undefined){
        if(contact.name) name.value = contact.name;
        if(contact.email) email.value = contact.email;
        if(contact.phone) phone.value = contact.phone;
        if(contact.country) country.value = contact.country;
        if(contact.cep) cep.value = String(contact.cep);
        if(contact.state) state.value = contact.state;
        if(contact.city) city.value = contact.city;
        if(contact.neighborhood) neighborhood.value = contact.neighborhood;
        if(contact.street_address) street_address.value = contact.street_address;
        if(contact.house_number) house_number.value = String(contact.house_number);
        if(contact.complement) complement.value = contact.complement;
        

      }
    }
    
    modal.show()
  }

  function fecharModal() {
    modal.hide()
  }
  
  async function deleteContact(id: number){
    try {
      const response = await fetch(`http://localhost:8000/api/contact/${id}`, {
        method: 'DELETE',
        headers: {
          "Accept": "application/json",
          "Content-Type": "application/json"
        }
      });

      // tenta converter o retorno em JSON, se houver
      let data = null;
      const contentType = response.headers.get("content-type");
      if (contentType && contentType.includes("application/json")) {
        data = await response.json();
      }

      // se não deu certo
      if (!response.ok) {
        showAlert('Erro!', data.message, 'error');
        return;
      }

      showAlert('Sucesso!', data.message, 'success');
      await loadContacts();

    } catch (error: any) {
      showAlert('Erro!', 'Erro inesperado ao excluir contato', 'error');
      console.error('Erro ao excluir contato:', error);
    }
  }

  // Mapeando para Option<string>
  const countriesOp = computed(() =>
    countries.value.map(c => ({
      label: c.nome,  // o que aparece no dropdown
      value: c.iso    // o que será armazenado em v-model
    }))
  );

  const checkCEP = (e: Event) => {
    const target = e.target as HTMLInputElement;
    let number = parseInt(target.value);
    if(!Number.isNaN(number) && target.value.length === 8){
      fetch(`https://viacep.com.br/ws/${number}/json/`)
        .then(response => response.json())
        .then(data => {
          if(city && data.localidade !== undefined){
            city.value = data.localidade;
          }else{
            city.value = '';
          }
          if(data !== undefined){
            country.value = 'BR';
            if(data.bairro !== undefined) neighborhood.value = data.bairro;
            if(data.logradouro !== undefined) street_address.value = data.logradouro;
            if(data.complemento !== undefined) complement.value = data.complemento;
            if(data.uf !== undefined) state.value = data.uf;
          }
        });
    }
  }


  onMounted(async() => {
    const collapse = document.getElementById('navbarSupportedContent')

    collapse?.addEventListener('show.bs.collapse', () => {
      isMenuOpen.value = true
    })

    collapse?.addEventListener('hide.bs.collapse', () => {
      isMenuOpen.value = false
    })

    const modalFormEl = document.getElementById('formModal')
    if (modalFormEl) {
      modal = new bootstrap.Modal(modalFormEl)
    }

    const response = await fetch('json/countries.json')
    countries.value = (await response.json());

    loadDefaultPicture()
    loadContacts();

  })

</script>

<template>
  <section class="d-flex justify-content-center align-content-start px-15 mt-5 flex-wrap w-100" style="height: 85%;">

    <nav id="nav-bar" class="d-flex navbar navbar-expand-lg bg-body-tertiary w-100 rounded shadow mb-3 bg-body-tertiary" style="height: 7vh;">
      <div class="container-fluid overflow-y-auto">
        <a class="navbar-brand" href="#"><img src="http://localhost:8000/favicon.ico" width="28"></a>
        <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarSupportedContent" aria-controls="navbarSupportedContent" aria-expanded="false" aria-label="Toggle navigation">
          <span class="navbar-toggler-icon"></span>
        </button>
        <div class="collapse navbar-collapse" id="navbarSupportedContent">
          <ul class="navbar-nav me-auto mb-2 mb-lg-0">
            <li class="nav-item" v-if="!isMenuOpen">
              <span class="nav-link active fs-5" aria-current="page" href="/">Agenda de Contatos</span>
            </li>
          </ul>
          <button class="btn btn-search" type="submit" @click="openModal(ACTION.create)">Criar novo</button>
        </div>
      </div>
    </nav>

    <div class="d-flex flex-wrap w-100 justify-content-center">
      <div class="container w-100 text-center">
        <h1>FILTROS</h1>
      </div>
      <div v-if="!paginator?.data?.length" class="container justify-content-center align-content-start w-100 h-100 d-flex flex-wrap overflow-y-auto">
        <h3 class="mt-5">Carregando lista de contatos...</h3>
      </div>
      <div class="container w-100 h-100 d-flex flex-wrap overflow-y-auto" style="max-height: 62vh;" v-if="paginator?.data?.length">
        <div v-for="contact in paginator.data" :key="contact.id" class="w-100 gap-1 mb-3"> 
          <div class="card p-1 card-contact">
            <div class="card-body d-flex gap-1">
              <div style="width: 12rem" name="Image Contact" class="d-flex justify-content-center align-content-center">
                <img 
                  :src="contact.picture ? contact.picture : defaultPicture ? defaultPicture : ''" 
                  alt="Imagem do contato" 
                  class="rounded-circle border border-black"
                  style="border-radius: 50%;object-fit: cover; width: 10rem; min-width: 10rem;aspect-ratio: 1 / 1;"
                />
              </div>
              <div name="Info Contact" style="width:88%">
                <h5 class="card-title">{{ contact.name }}</h5>
                <p class="mb-0">{{ contact.email }}</p>
                <p class="mb-0">
                  {{ (contact.country ? contact.country : '') + (contact.country ? ' - ':'') + 
                    (contact.cep ? contact.cep : '') + (contact.cep ? ' - ':'') +
                    (contact.state ? contact.state : '') + (contact.state ? ' - ':'') + 
                    (contact.city ? contact.city : '') + (contact.city ? ' - ':'') +
                    (contact.neighborhood ? contact.neighborhood : '') + (contact.neighborhood ? ' - ':'') +
                    (contact.street_address ? contact.street_address : '') + (contact.street_address ? ' - ':'') +
                    (contact.house_number ? contact.house_number : '') + (contact.house_number ? ' - ':'') +
                    (contact.complement ? contact.complement : '') + (contact.complement ? '':'')}}
                </p>
                <p>
                  {{ 
                    contact.phone ? contact.phone : '' 
                  }}
                </p>
                <div class="w-100 d-flex text-end justify-content-end gap-2">
                  <a class="btn btn-secondary" @click="openModal(ACTION.edit, contact.id)">Alterar</a>
                  <a class="btn btn-danger" @click="deleteContact(contact.id)">Remover</a>
                </div>
              </div>
            </div>
          </div>
        </div>
      </div>
    </div>

    <div
      class="modal fade"
      id="formModal"
      tabindex="-1"
      aria-labelledby="formModalLabel"
      aria-hidden="true"
    >
      <div class="modal-dialog modal-dialog-centered formModal">
        <div class="modal-content">
          <div class="modal-header">
            <h1 class="modal-title fs-5" id="formModalLabel">{{ formAction==='create' ? 'Criar':'Editar' }} Contato</h1>
            <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Fechar"></button>
          </div>
          <div class="modal-body">
            <form>
              <div class="form-row">
                <div class="row mb-3">
                  <div class="col-md-6">
                    <label for="formModalName" class="form-label">Nome</label>
                    <input type="text" class="form-control" v-model="name" id="formModalName" autocomplete="name" placeholder="Nome">
                  </div>
                  <div class="col-md-6">
                    <label for="formModalEmail" class="form-label">Email</label>
                    <input :disabled="formAction === ACTION.edit" type="email" class="form-control" v-model="email" id="formModalEmail" autocomplete="email" placeholder="Email">
                  </div>
                </div>
              </div>
              <div class="form-row">
                <div class="row mb-3">
                  <div class="col-md-3">
                    <label for="formModalPhone" class="form-label">Telefone</label>
                    <input type="text" class="form-control" v-model="phone" id="formModalPhone" autocomplete="phone" placeholder="+55 (99) 99999-9999">
                  </div>
                  <div class="col-md-3">
                    <label for="formModalCep" class="form-label">CEP</label>
                    <input type="number" class="form-control" v-model="cep" id="formModalCep" autocomplete="cep" @input="checkCEP" placeholder="89217100">
                  </div>
                  <div class="col-md-6">
                    <label for="formModalCountry" class="form-label">País</label>
                    <VueSelect
                      v-model="country"
                      :options="countriesOp"
                      placeholder="Selecione um país"
                    />
                  </div>
                </div>
              </div>
              <div class="form-row">
                <div class="row mb-3">
                  <div class="col-md-6">
                    <label for="formModalCity" class="form-label">Cidade</label>
                    <input type="text" class="form-control" v-model="city" id="formModalCity" autocomplete="city" placeholder="Cidade">
                  </div>
                  <div class="col-md-3">
                    <label for="formModalNeighborhood" class="form-label">Bairro</label>
                    <input type="text" class="form-control" v-model="neighborhood" id="formModalNeighborhood" autocomplete="neighborhood" placeholder="Bairro">
                  </div>
                  <div class="col-md-3">
                    <label for="formModalState" class="form-label">Estado</label>
                    <input type="text" class="form-control" v-model="state" id="formModalState" autocomplete="state" placeholder="Estado">
                  </div>
                </div>
              </div>
              <div class="form-row">
                <div class="row mb-3">
                  <div class="col-md-12">
                    <label for="formModalStreetAddress" class="form-label">Logradouro</label>
                    <input type="text" class="form-control" v-model="street_address" id="formModalStreetAddress" autocomplete="street" placeholder="Logradouro">
                  </div>
                </div>
              </div>
              <div class="form-row">
                <div class="row mb-3">
                  <div class="col-md-3">
                    <label for="formModalHouseNumber" class="form-label">Número</label>
                    <input class="form-control" v-model="house_number" id="formModalHouseNumber" rows="3" placeholder="Número"/>
                  </div>
                  <div class="col-md-3">
                    <label for="formModalComplement" class="form-label">Complemento</label>
                    <input class="form-control" v-model="complement" id="formModalComplement" rows="3" placeholder="Complemento"/>
                  </div>
                  <div class="col-md-6">
                    <label for="formModalPicture" class="form-label">Imagem do contato</label>
                    <input class="form-control" @change="onFileChange" type="file" id="formModalPicture" rows="3"/>
                  </div>
                </div>
              </div>
            </form>
          </div>
          <div class="modal-footer">
            <button type="button" class="btn btn-secondary" @click="fecharModal">Fechar</button>
            <button type="button" class="btn btn-primary" @click="create">Cadastrar</button>
          </div>
        </div>
      </div>
    </div>
  </section>
</template>

<style scoped>
  .px-15 {
    padding-left: 15vw;
    padding-right: 15vw;
  }
  .btn-search {
    background: linear-gradient(90deg, #712f8f, #8654d8);
    color: #fffefe;
    transition: ease-in-out 2s;
  }
  .btn-search:hover {
    background: linear-gradient(90deg, #5f2879, #7448bb);
    color: #fffefe;
    transition: ease-in-out 2s;
  }
  .formModal{
    width: 50%;
    max-width: 100vw;
  }
  .card-contact{
    background-color: #fefdff;
    width: 100% !important;
  }
</style>
