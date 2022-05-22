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
            associateSedes: 'api/associateSedes',
            associateEmpresas: 'api/associateEmpresas',
            getTipoPago: 'api/getTipoPago',
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

            associateSedeEmpresa: 'api/associateSedeEmpresa',
            getEmpresasAsociadas: 'api/getEmpresasAsociadas',
            
            setTipoPago: 'api/setTipoPago',
            getTipoById: 'api/getTipoById',
        },

        put: {
            deleteSedeById: 'api/deleteSedeById',
            deleteEmpresaById: 'api/deleteEmpresaById',
            updateTipoPagoById: 'api/updateTipoPagoById',
            deleteTipoById: 'api/deleteTipoById',
        }
    }
]