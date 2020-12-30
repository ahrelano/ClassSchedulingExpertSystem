<?php
namespace App\Controller;

use App\Controller\AppController;

class TeachersController extends AppController
{

    public function index()
    {
        $this->paginate = [
            'contain' => ['Positions', 'Subjects']
        ];
        $teachers = $this->paginate($this->Teachers);

        $this->set(compact('teachers'));
        $this->set('_serialize', ['teachers']);
    }

    public function view($id = null)
    {
        $teacher = $this->Teachers->get($id, [
            'contain' => ['Positions', 'Subjects']
        ]);

        $this->set('teacher', $teacher);
        $this->set('_serialize', ['teacher']);
    }

    public function add()
    {
        $teacher = $this->Teachers->newEntity();
        if ($this->request->is('post')) {
            $check_principal = $this->Teachers->find('all')->where(['Teachers.position_id'=>5]);
            if ($check_principal == true  && $this->request->data['position_id'] == 5) {
                $this->Flash->error(__('There\'s already a principal saved in our database.'));
            }else{
                $teacher = $this->Teachers->patchEntity($teacher, $this->request->getData());
                if ($this->Teachers->save($teacher)) {
                    $this->Flash->success(__('The teacher has been saved.'));

                    return $this->redirect(['action' => 'add']);
                }
                $this->Flash->error(__('The teacher could not be saved. Please, try again.'));
            }
        }
        $positions = $this->Teachers->Positions->find('list', ['limit' => 200])->order(["Positions.position"]);
        $subjects = $this->Teachers->Subjects->find('list', ['limit' => 200])->order(["Subjects.subject"]);
        $this->set(compact('teacher', 'positions', 'subjects'));
        $this->set('_serialize', ['teacher']);
    }

    public function edit($id = null)
    {
        $teacher = $this->Teachers->get($id, [
            'contain' => []
        ]);
        if ($this->request->is(['patch', 'post', 'put'])) {
            $teacher = $this->Teachers->patchEntity($teacher, $this->request->getData());
            if ($this->Teachers->save($teacher)) {
                $this->Flash->success(__('The teacher has been saved.'));

                return $this->redirect(['action' => 'index']);
            }
            $this->Flash->error(__('The teacher could not be saved. Please, try again.'));
        }
        $positions = $this->Teachers->Positions->find('list', ['limit' => 200])->order(["Positions.position"]);
        $subjects = $this->Teachers->Subjects->find('list', ['limit' => 200])->order(["Subjects.subject"]);
        $this->set(compact('teacher', 'positions', 'subjects'));
        $this->set('_serialize', ['teacher']);
    }

    public function delete($id = null)
    {
        $this->request->allowMethod(['post', 'delete']);
        $teacher = $this->Teachers->get($id);
        if ($this->Teachers->delete($teacher)) {
            $this->Flash->success(__('The teacher has been deleted.'));
        } else {
            $this->Flash->error(__('The teacher could not be deleted. Please, try again.'));
        }

        return $this->redirect(['action' => 'index']);
    }

    public function generate($id = null, $generate = null)
    {
        $this->request->allowMethod(['post','put']);
        $teacher = $this->Teachers->get($id);
        $teacher->generate = $generate;
        if ($this->Teachers->save($teacher)) {
            $this->Flash->success(__('The teacher has been generated.'));
        } else {
            $this->Flash->error(__('The teacher could not be generated. Please, try again.'));
        }

        return $this->redirect(['action' => 'index']);
    }
}
