export const router = [
    {
        createUser: 'api/createUser'
    },
    {
        get: {
            departamentos: 'api/departamentos',
            municipios: 'api/municipios',
        },
        post: {
            departamentById: 'api/departamentoById',
            updateDepartamenoById: 'api/updateDepartamenoById',
            deleteDepartament: 'api/deleteDepartament',

            municipioById: 'api/municipioById',
            updateMunicipioById: 'api/updateMunicipioById',
            deleteMunicipio: 'api/deleteMunicipio',
        }
    }
]