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
            getTipoGasto: 'api/getTipoGasto',
            getMedida: 'api/getMedida',
            getStringCorrelativo: 'api/getStringCorrelativo',
            sat: 'https://dev.mineco.gob.gt/web-quejaini/rs/proveedores/aprobar?nit=',
            getProveedores: 'api/getProveedores',
            getClientes: 'api/getClientes',
            getProductos: 'api/getProductos',
            getMedidas:'api/getMedidas',
            getSucursal:'api/getSucursal',
            getInventario: 'api/getInventario',
            listProductosInventario: 'api/listProductosInventario',
            cargarMisRequisiciones: 'api/cargarMisRequisiciones',
            cargarrequisicionesAprobar: 'api/cargarrequisicionesAprobar',
            cargarrequisicionesAutorizar: 'api/cargarrequisicionesAutorizar',
            cargarrequisicionesDespacho: 'api/cargarrequisicionesDespacho',
            generateCorrelativo: 'api/generateCorrelativo',
            getString: 'api/getString'
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
            setTipoGasto: 'api/setTipoGasto',
            getTipoGastoById: 'api/getTipoGastoById',
            setMedida: 'api/setMedida',
            getMedidaById: 'api/getMedidaById',
            setStringCorrelativo: 'api/setStringCorrelativo',
            getStringCorrelativoById: 'api/getStringCorrelativoById',
            setCorrelativoInitial: 'api/setCorrelativoInitial',
            getData: 'api/getData',
            setData: 'api/setData',
            updateData: 'api/updateData',
            getDataById: 'api/getDataById',
            setProveedores: 'api/setProveedores',
            getProveedoresById: 'api/getProveedoresById',
            setClientes: 'api/setClientes',
            getClienteById: 'api/getClienteById',
            setInventario: 'api/setInventario',
            findProducto: 'api/findProducto',
            setRequisicion: 'api/setRequisicion',
            RequisicionesAprobarInfo: 'api/RequisicionesAprobarInfo',
            aprobarRequisicion: 'api/aprobarRequisicion',
            autorizarRequisicion: 'api/autorizarRequisicion',
            despacharRequisicion: 'api/despacharRequisicion',
            pdf: 'api/pdf',
            cargarItems: 'api/cargarItems',
            setFactura: 'api/setFactura'
        },
        
        put: {
            deleteSedeById: 'api/deleteSedeById',
            deleteEmpresaById: 'api/deleteEmpresaById',
            updateTipoPagoById: 'api/updateTipoPagoById',
            deleteTipoById: 'api/deleteTipoById',
            deleteTipoGastoById: 'api/deleteTipoGastoById',
            updateTipoGastoById: 'api/updateTipoGastoById',
            deleteMedidaById: 'api/deleteMedidaById',
            updateMedidaById: 'api/updateMedidaById',
            deleteStringCorrelativoById: 'api/deleteStringCorrelativoById',
            updateStringCorrelativoById: 'api/updateStringCorrelativoById',
            getUpdateDataById: 'api/getUpdateDataById',
            getDeleteDataById: 'api/getDeleteDataById',
            updateProveedores: 'api/updateProveedores',
            deleteProveedores: 'api/deleteProveedores',
            deleteClientes: 'api/deleteClientes',
            updateStock: 'api/updateStock',
            deleteStock: 'api/deleteStock',
            rechazarRequisicion: 'api/rechazarRequisicion'
        }
    }
]