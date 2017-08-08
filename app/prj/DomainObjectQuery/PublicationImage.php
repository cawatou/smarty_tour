<?php

class DomainObjectQuery_PublicationImage extends DomainObjectQuery
{
    /**
     * @param int $id
     * @return DomainObjectModel_PublicationImage|null
     */
    public function findById($id)
    {
        $qb = $this->getQueryBuilder()
            ->select('pi')
            ->from('DomainObjectModel_PublicationImage', 'pi')
            ->where('pi.publication_image_id = ?');

        return $this->getSingleFound($qb, array($id));
    }

    /**
     * @param $publication_id
     * @return DomainObjectModel_PublicationImage|null
     */
    public function findCurrentCover($publication_id)
    {
        $qb = $this->getQueryBuilder()
            ->select('pi')
            ->from('DomainObjectModel_PublicationImage', 'pi')
            ->where('pi.publication_image_is_cover = ?')
            ->andWhere('pi.publication_id = ?');

        return $this->getSingleFound($qb, array(1, $publication_id));
    }

    /**
     * @param int $image_qnt
     * @param int $publication_id
     * @return DomainObjectModel_PublicationImage|null
     */
    public function findLeftImage($image_qnt, $publication_id)
    {
        $qb = $this->getQueryBuilder()
            ->select('pi')
            ->from('DomainObjectModel_PublicationImage', 'pi')
            ->where('pi.publication_image_qnt < ?')
            ->andWhere('pi.publication_id = ?')
            ->orderBy('pi.publication_image_qnt', 'DESC')
            ->offset(0)
            ->limit(1);
        return $this->getSingleFound($qb, array($image_qnt, $publication_id));
    }

    /**
     * @param int $image_qnt
     * @param int $publication_id
     * @return DomainObjectModel_PublicationImage|null
     */
    public function findRightImage($image_qnt, $publication_id)
    {
        $qb = $this->getQueryBuilder()
            ->select('pi')
            ->from('DomainObjectModel_PublicationImage', 'pi')
            ->where('pi.publication_image_qnt > ?')
            ->andWhere('pi.publication_id = ?')
            ->orderBy('pi.publication_image_qnt', 'ASC')
            ->offset(0)
            ->limit(1);

        return $this->getSingleFound($qb, array($image_qnt, $publication_id));
    }    
}
