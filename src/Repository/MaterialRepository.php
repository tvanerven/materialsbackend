<?php

namespace App\Repository;

use App\Entity\Material;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @method Material|null find($id, $lockMode = null, $lockVersion = null)
 * @method Material|null findOneBy(array $criteria, array $orderBy = null)
 * @method Material[]    findAll()
 * @method Material[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class MaterialRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Material::class);
    }

    public function findAllMaterial()
    {
        $query = $this->getEntityManager()
            ->createQuery('SELECT a FROM App\Entity\Annotation a');

        $annotations =  $query->getResult();
        $materials = [];

        foreach ($annotations as $annotation) {
            $material = $annotation->getMaterial();
            $concept = $annotation->getConcept();

            $alreadyInsertedMaterial = $this->getMaterialByIdInArray($material->getId(), $materials);

            if ($alreadyInsertedMaterial === null) {
                $material->addConcept($concept);
                array_push($materials, $material);
            } else {
                $alreadyInsertedMaterial->addConcept($concept);
            }
        }

        return $materials;
    }

    /**
     * @param int $materialId
     * @param Material[] $materials
     */
    private function &getMaterialByIdInArray(int $materialId, array &$materials): ?Material
    {
        $alreadyInsertedMaterial = null;
        foreach ($materials as $material) {
            if ($material->getId() === $materialId) {
                $alreadyInsertedMaterial = $material;
            }
        }
        return $alreadyInsertedMaterial;
    }
}
