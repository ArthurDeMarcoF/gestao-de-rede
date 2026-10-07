<?php

class Especialidades extends TRecord
{
    const TABLENAME  = 'especialidades';
    const PRIMARYKEY = 'id';
    const IDPOLICY   =  'serial'; // {max, serial}

    const CREATEDAT  = 'data_criacao';
    const UPDATEDAT  = 'data_alteracao';

    

    /**
     * Constructor method
     */
    public function __construct($id = NULL, $callObjectLoad = TRUE)
    {
        parent::__construct($id, $callObjectLoad);
        parent::addAttribute('data_criacao');
        parent::addAttribute('data_alteracao');
        parent::addAttribute('especialidade');
            
    }

    /**
     * Method getAcordos
     */
    public function getAcordos()
    {
        $criteria = new TCriteria;
        $criteria->add(new TFilter('especialidades_id', '=', $this->id));
        return Acordo::getObjects( $criteria );
    }
    /**
     * Method getCooperadosEspecialidadess
     */
    public function getCooperadosEspecialidadess()
    {
        $criteria = new TCriteria;
        $criteria->add(new TFilter('especialidades_id', '=', $this->id));
        return CooperadosEspecialidades::getObjects( $criteria );
    }
    /**
     * Method getCredenciadosEspecialidadess
     */
    public function getCredenciadosEspecialidadess()
    {
        $criteria = new TCriteria;
        $criteria->add(new TFilter('especialidades_id', '=', $this->id));
        return CredenciadosEspecialidades::getObjects( $criteria );
    }
    /**
     * Method getCatalogoEspecialidades
     */
    public function getCatalogoEspecialidades()
    {
        $criteria = new TCriteria;
        $criteria->add(new TFilter('especialidades_id', '=', $this->id));
        return CatalogoEspecialidade::getObjects( $criteria );
    }
    /**
     * Method getMedicosPfEspecialidadess
     */
    public function getMedicosPfEspecialidadess()
    {
        $criteria = new TCriteria;
        $criteria->add(new TFilter('especialidades_id', '=', $this->id));
        return MedicosPfEspecialidades::getObjects( $criteria );
    }

    public function set_acordo_especialidades_to_string($acordo_especialidades_to_string)
    {
        if(is_array($acordo_especialidades_to_string))
        {
            $values = Especialidades::where('id', 'in', $acordo_especialidades_to_string)->getIndexedArray('especialidade', 'especialidade');
            $this->acordo_especialidades_to_string = implode(', ', $values);
        }
        else
        {
            $this->acordo_especialidades_to_string = $acordo_especialidades_to_string;
        }

        $this->vdata['acordo_especialidades_to_string'] = $this->acordo_especialidades_to_string;
    }

    public function get_acordo_especialidades_to_string()
    {
        if(!empty($this->acordo_especialidades_to_string))
        {
            return $this->acordo_especialidades_to_string;
        }
    
        $values = Acordo::where('especialidades_id', '=', $this->id)->getIndexedArray('especialidades_id','{especialidades->especialidade}');
        return implode(', ', $values);
    }

    public function set_cooperados_especialidades_cooperados_to_string($cooperados_especialidades_cooperados_to_string)
    {
        if(is_array($cooperados_especialidades_cooperados_to_string))
        {
            $values = Cooperados::where('id', 'in', $cooperados_especialidades_cooperados_to_string)->getIndexedArray('nome', 'nome');
            $this->cooperados_especialidades_cooperados_to_string = implode(', ', $values);
        }
        else
        {
            $this->cooperados_especialidades_cooperados_to_string = $cooperados_especialidades_cooperados_to_string;
        }

        $this->vdata['cooperados_especialidades_cooperados_to_string'] = $this->cooperados_especialidades_cooperados_to_string;
    }

    public function get_cooperados_especialidades_cooperados_to_string()
    {
        if(!empty($this->cooperados_especialidades_cooperados_to_string))
        {
            return $this->cooperados_especialidades_cooperados_to_string;
        }
    
        $values = CooperadosEspecialidades::where('especialidades_id', '=', $this->id)->getIndexedArray('cooperados_id','{cooperados->nome}');
        return implode(', ', $values);
    }

    public function set_cooperados_especialidades_especialidades_to_string($cooperados_especialidades_especialidades_to_string)
    {
        if(is_array($cooperados_especialidades_especialidades_to_string))
        {
            $values = Especialidades::where('id', 'in', $cooperados_especialidades_especialidades_to_string)->getIndexedArray('especialidade', 'especialidade');
            $this->cooperados_especialidades_especialidades_to_string = implode(', ', $values);
        }
        else
        {
            $this->cooperados_especialidades_especialidades_to_string = $cooperados_especialidades_especialidades_to_string;
        }

        $this->vdata['cooperados_especialidades_especialidades_to_string'] = $this->cooperados_especialidades_especialidades_to_string;
    }

    public function get_cooperados_especialidades_especialidades_to_string()
    {
        if(!empty($this->cooperados_especialidades_especialidades_to_string))
        {
            return $this->cooperados_especialidades_especialidades_to_string;
        }
    
        $values = CooperadosEspecialidades::where('especialidades_id', '=', $this->id)->getIndexedArray('especialidades_id','{especialidades->especialidade}');
        return implode(', ', $values);
    }

    public function set_credenciados_especialidades_credenciados_to_string($credenciados_especialidades_credenciados_to_string)
    {
        if(is_array($credenciados_especialidades_credenciados_to_string))
        {
            $values = Credenciados::where('id', 'in', $credenciados_especialidades_credenciados_to_string)->getIndexedArray('nome', 'nome');
            $this->credenciados_especialidades_credenciados_to_string = implode(', ', $values);
        }
        else
        {
            $this->credenciados_especialidades_credenciados_to_string = $credenciados_especialidades_credenciados_to_string;
        }

        $this->vdata['credenciados_especialidades_credenciados_to_string'] = $this->credenciados_especialidades_credenciados_to_string;
    }

    public function get_credenciados_especialidades_credenciados_to_string()
    {
        if(!empty($this->credenciados_especialidades_credenciados_to_string))
        {
            return $this->credenciados_especialidades_credenciados_to_string;
        }
    
        $values = CredenciadosEspecialidades::where('especialidades_id', '=', $this->id)->getIndexedArray('credenciados_id','{credenciados->nome}');
        return implode(', ', $values);
    }

    public function set_credenciados_especialidades_especialidades_to_string($credenciados_especialidades_especialidades_to_string)
    {
        if(is_array($credenciados_especialidades_especialidades_to_string))
        {
            $values = Especialidades::where('id', 'in', $credenciados_especialidades_especialidades_to_string)->getIndexedArray('especialidade', 'especialidade');
            $this->credenciados_especialidades_especialidades_to_string = implode(', ', $values);
        }
        else
        {
            $this->credenciados_especialidades_especialidades_to_string = $credenciados_especialidades_especialidades_to_string;
        }

        $this->vdata['credenciados_especialidades_especialidades_to_string'] = $this->credenciados_especialidades_especialidades_to_string;
    }

    public function get_credenciados_especialidades_especialidades_to_string()
    {
        if(!empty($this->credenciados_especialidades_especialidades_to_string))
        {
            return $this->credenciados_especialidades_especialidades_to_string;
        }
    
        $values = CredenciadosEspecialidades::where('especialidades_id', '=', $this->id)->getIndexedArray('especialidades_id','{especialidades->especialidade}');
        return implode(', ', $values);
    }

    public function set_catalogo_especialidade_catalogo_to_string($catalogo_especialidade_catalogo_to_string)
    {
        if(is_array($catalogo_especialidade_catalogo_to_string))
        {
            $values = Catalogo::where('id', 'in', $catalogo_especialidade_catalogo_to_string)->getIndexedArray('nome', 'nome');
            $this->catalogo_especialidade_catalogo_to_string = implode(', ', $values);
        }
        else
        {
            $this->catalogo_especialidade_catalogo_to_string = $catalogo_especialidade_catalogo_to_string;
        }

        $this->vdata['catalogo_especialidade_catalogo_to_string'] = $this->catalogo_especialidade_catalogo_to_string;
    }

    public function get_catalogo_especialidade_catalogo_to_string()
    {
        if(!empty($this->catalogo_especialidade_catalogo_to_string))
        {
            return $this->catalogo_especialidade_catalogo_to_string;
        }
    
        $values = CatalogoEspecialidade::where('especialidades_id', '=', $this->id)->getIndexedArray('catalogo_id','{catalogo->nome}');
        return implode(', ', $values);
    }

    public function set_catalogo_especialidade_especialidades_to_string($catalogo_especialidade_especialidades_to_string)
    {
        if(is_array($catalogo_especialidade_especialidades_to_string))
        {
            $values = Especialidades::where('id', 'in', $catalogo_especialidade_especialidades_to_string)->getIndexedArray('especialidade', 'especialidade');
            $this->catalogo_especialidade_especialidades_to_string = implode(', ', $values);
        }
        else
        {
            $this->catalogo_especialidade_especialidades_to_string = $catalogo_especialidade_especialidades_to_string;
        }

        $this->vdata['catalogo_especialidade_especialidades_to_string'] = $this->catalogo_especialidade_especialidades_to_string;
    }

    public function get_catalogo_especialidade_especialidades_to_string()
    {
        if(!empty($this->catalogo_especialidade_especialidades_to_string))
        {
            return $this->catalogo_especialidade_especialidades_to_string;
        }
    
        $values = CatalogoEspecialidade::where('especialidades_id', '=', $this->id)->getIndexedArray('especialidades_id','{especialidades->especialidade}');
        return implode(', ', $values);
    }

    public function set_medicos_pf_especialidades_especialidades_to_string($medicos_pf_especialidades_especialidades_to_string)
    {
        if(is_array($medicos_pf_especialidades_especialidades_to_string))
        {
            $values = Especialidades::where('id', 'in', $medicos_pf_especialidades_especialidades_to_string)->getIndexedArray('especialidade', 'especialidade');
            $this->medicos_pf_especialidades_especialidades_to_string = implode(', ', $values);
        }
        else
        {
            $this->medicos_pf_especialidades_especialidades_to_string = $medicos_pf_especialidades_especialidades_to_string;
        }

        $this->vdata['medicos_pf_especialidades_especialidades_to_string'] = $this->medicos_pf_especialidades_especialidades_to_string;
    }

    public function get_medicos_pf_especialidades_especialidades_to_string()
    {
        if(!empty($this->medicos_pf_especialidades_especialidades_to_string))
        {
            return $this->medicos_pf_especialidades_especialidades_to_string;
        }
    
        $values = MedicosPfEspecialidades::where('especialidades_id', '=', $this->id)->getIndexedArray('especialidades_id','{especialidades->especialidade}');
        return implode(', ', $values);
    }

    public function set_medicos_pf_especialidades_medicos_pf_to_string($medicos_pf_especialidades_medicos_pf_to_string)
    {
        if(is_array($medicos_pf_especialidades_medicos_pf_to_string))
        {
            $values = MedicosPf::where('id', 'in', $medicos_pf_especialidades_medicos_pf_to_string)->getIndexedArray('id', 'id');
            $this->medicos_pf_especialidades_medicos_pf_to_string = implode(', ', $values);
        }
        else
        {
            $this->medicos_pf_especialidades_medicos_pf_to_string = $medicos_pf_especialidades_medicos_pf_to_string;
        }

        $this->vdata['medicos_pf_especialidades_medicos_pf_to_string'] = $this->medicos_pf_especialidades_medicos_pf_to_string;
    }

    public function get_medicos_pf_especialidades_medicos_pf_to_string()
    {
        if(!empty($this->medicos_pf_especialidades_medicos_pf_to_string))
        {
            return $this->medicos_pf_especialidades_medicos_pf_to_string;
        }
    
        $values = MedicosPfEspecialidades::where('especialidades_id', '=', $this->id)->getIndexedArray('medicos_pf_id','{medicos_pf->id}');
        return implode(', ', $values);
    }

    
}

