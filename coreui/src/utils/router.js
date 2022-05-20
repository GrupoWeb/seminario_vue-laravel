export const router = [
    {
        createUser: 'api/createUser'
    },
    {
        get: {
            departamentos: 'api/departamentos',
            municipios: 'api/municipios',

            getSede: 'api/getSedes',

            getEmpresas: 'api/getEmpresas',
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
            updateSedeById: 'api/updateSedeById',

            setEmpresa: 'api/setEmpresa',
            getEmpresaById: 'api/getEmpresaById',
            updateEmpresa: 'api/updateEmpresa',

        },

        put: {
            deleteSedeById: 'api/deleteSedeById',
            deleteEmpresaById: 'api/deleteEmpresaById',
        }
    }
]