<?php

namespace app\models;

use Yii;
use yii\base\Model;
use yii\data\ActiveDataProvider;
use app\models\Pengelola;

/**
 * PengelolaSearch represents the model behind the search form about `app\models\Pengelola`.
 */
class PengelolaSearch extends Pengelola
{
    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['id_pengelola'], 'integer'],
            [['nama_lengkap', 'email', 'password', 'alamat', 'nomor_handphone', 'pekerjaan', 'usia', 'agama', 'foto', 'createdAt', 'updatedAt'], 'safe'],
        ];
    }

    /**
     * @inheritdoc
     */
    public function scenarios()
    {
        // bypass scenarios() implementation in the parent class
        return Model::scenarios();
    }

    /**
     * Creates data provider instance with search query applied
     *
     * @param array $params
     *
     * @return ActiveDataProvider
     */
    public function search($params)
    {
        $query = Pengelola::find();

        $dataProvider = new ActiveDataProvider([
            'query' => $query,
        ]);

        $this->load($params);

        if (!$this->validate()) {
            // uncomment the following line if you do not want to return any records when validation fails
            // $query->where('0=1');
            return $dataProvider;
        }

        $query->andFilterWhere([
            'id_pengelola' => $this->id_pengelola,
            'createdAt' => $this->createdAt,
            'updatedAt' => $this->updatedAt,
        ]);

        $query->andFilterWhere(['like', 'nama_lengkap', $this->nama_lengkap])
            ->andFilterWhere(['like', 'email', $this->email])
            ->andFilterWhere(['like', 'password', $this->password])
            ->andFilterWhere(['like', 'alamat', $this->alamat])
            ->andFilterWhere(['like', 'nomor_handphone', $this->nomor_handphone])
            ->andFilterWhere(['like', 'pekerjaan', $this->pekerjaan])
            ->andFilterWhere(['like', 'usia', $this->usia])
            ->andFilterWhere(['like', 'agama', $this->agama])
            ->andFilterWhere(['like', 'foto', $this->foto]);

        return $dataProvider;
    }
}
