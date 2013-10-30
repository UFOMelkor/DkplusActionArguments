<?php
namespace DkplusActionArgumentsTest\Controller\TestAsset;

use Album\Model\Album;
use DkplusActionArguments\Controller\AbstractActionController;
use Zend\View\Model\ViewModel;

class AlbumController extends AbstractActionController
{
    public function indexAction(array $albums)
    {
        return new ViewModel(array('albums' => $albums));
    }

    public function deleteAction(Album $album)
    {
        $request = $this->getRequest();
        if ($request->isPost()) {
            $del = $request->getPost('del', 'No');

            if ($del == 'Yes') {
                $this->getAlbumTable()->deleteAlbum($album->id);
            }

            // Redirect to list of albums
            return $this->redirect()->toRoute('album');
        }

        return array(
            'id'    => $album->id,
            'album' => $album
        );
    }
}
 