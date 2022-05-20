export const router = [
    {
        createUser: 'api/createUser'
    },
    {
        get: {
            departamentos: 'api/departamentos',
            municipios: 'api/municipios',

            getSede: 'api/getSedes',
        },
        post: {
            departamentById: 'api/departamentoById',
            updateDepartamenoById: 'api/updateDepartamenoById',
            deleteDepartament: 'api/deleteDepartament',

            municipioById: 'api/municipioById',
            updateMunicipioById: 'api/updateMunicipioById',
            deleteMunicipio: 'api/deleteMunicipio',

            setSede: 'api/setSede',
            getMunicipioByIdSede: 'api/getMunicipioByIdSede',
            getSedeById: 'api/getSedeById',
            updateSedeById: 'api/updateSedeById'

        }
    }
]