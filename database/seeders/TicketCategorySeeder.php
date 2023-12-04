<?php

namespace Database\Seeders;

use App\Models\TicketCategory;
use Illuminate\Database\Seeder;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;

class TicketCategorySeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        
        $categories = $this->categories();

        foreach ($categories as $category) {
            $newCategory = $this->createCategory($category);

            foreach ($category['childrens'] as $children) {
                $newChildren = $this->createCategory($children, $newCategory->id);

                foreach ($children['childrens'] as $grandchildren) {
                    $this->createCategory($grandchildren, $newChildren->id);
                }
            }
        }

    }

    public function createCategory($category, $parent_id = null){
        $newCategory = TicketCategory::factory()->create([
            'parent_id' => $parent_id,
            'name' => $category['name'],
        ]);

        return $newCategory;
    }

    public function categories(){
        $categories = [

            [
                'name' => 'Configuración',
                'childrens' => []
            ],

            [
                'name' => 'Falla',
                'childrens' => [
                    [
                        'name' => 'Cableado',
                        'childrens' => [
                            [
                                'name' => 'Drop de Datos/Voz',
                                'childrens' => []
                            ],
                            [
                                'name' => 'Fibra Óptica',
                                'childrens' => []
                            ],
                            [
                                'name' => 'Otros (Patch Cords)',
                                'childrens' => []
                            ]
                        ]
                    ],
                    [
                        'name' => 'Equipo Datos',
                        'childrens' => [
                            [
                                'name' => 'LAN Switch',
                                'childrens' => []
                            ],
                            [
                                'name' => 'WAN Router',
                                'childrens' => []
                            ],
                            [
                                'name' => 'WLAN Access Point, Controller',
                                'childrens' => []
                            ],
                            [
                                'name' => 'Seguridad (Firewall, IPS, etc.)',
                                'childrens' => []
                            ]
                        ]
                    ],
                    [
                        'name' => 'Equipo Voz',
                        'childrens' => [
                            [
                                'name' => 'Server',
                                'childrens' => []
                            ],
                            [
                                'name' => 'Gateway',
                                'childrens' => []
                            ],
                            [
                                'name' => 'Módulo',
                                'childrens' => []
                            ],
                            [
                                'name' => 'Tarjeta',
                                'childrens' => []
                            ],
                            [
                                'name' => 'Correo de voz',
                                'childrens' => []
                            ]
                        ]
                    ],
                    [
                        'name' => 'CCTV',
                        'childrens' => [
                            [
                                'name' => 'DVR',
                                'childrens' => []
                            ],
                            [
                                'name' => 'Cámara',
                                'childrens' => []
                            ]
                        ]
                    ],
                    [
                        'name' => 'Voceo',
                        'childrens' => [
                            [
                                'name' => 'Amplificador',
                                'childrens' => []
                            ]
                        ]
                    ]
                ]
            ],

            [
                'name' => 'Instalación',
                'childrens' => [
                    [
                        'name' => 'Cableado UTP Datos/Voz',
                        'childrens' => []
                    ],
                    [
                        'name' => 'Cableado Fibra Óptica',
                        'childrens' => []
                    ],
                    [
                        'name' => 'Cableado Voceo',
                        'childrens' => []
                    ],
                    [
                        'name' => 'Cableado CCTV',
                        'childrens' => []
                    ],
                    [
                        'name' => 'Equipo de Datos LAN',
                        'childrens' => []
                    ],
                    [
                        'name' => 'Equipo de Datos WAN',
                        'childrens' => []
                    ],
                    [
                        'name' => 'Firewall',
                        'childrens' => []
                    ],
                    [
                        'name' => 'Equipo UPS',
                        'childrens' => []
                    ],
                    [
                        'name' => 'Equipo CCTV/Cámaras',
                        'childrens' => []
                    ],
                    [
                        'name' => 'Equipo Control de Acceso',
                        'childrens' => []
                    ]
                ]    
            ],
            
            [
                'name' => 'Levantamiento',
                'childrens' => [
                    [
                        'name' => 'Datos/Voz',
                        'childrens' => []
                    ],
                    [
                        'name' => 'CCTV',
                        'childrens' => []
                    ],
                    [
                        'name' => 'Voceo/Paging',
                        'childrens' => []
                    ],
                    [
                        'name' => 'Control de Acceso',
                        'childrens' => []
                    ]
                ]
            ],

            [
                'name' => 'Otra',
                'childrens' => []
            ],

            [
                'name' => 'Servicio',
                'childrens' => [
                    [
                        'name' => 'Mantenimiento',
                        'childrens' => [
                            [
                                'name' => 'Mayor',
                                'childrens' => []
                            ],
                            [
                                'name' => 'Menor',
                                'childrens' => []
                            ]
                        ]
                    ],
                    [
                        'name' => 'Monitoreo',
                        'childrens' => []
                    ],
                    [
                        'name' => 'Configuración',
                        'childrens' => []
                    ],
                    [
                        'name' => 'Revisión de Sitio/Oficina',
                        'childrens' => []
                    ]
                ]
            ],

            [
                'name' => 'Suministro',
                'childrens' => [
                    [
                        'name' => 'Entrega de Material',
                        'childrens' => []
                    ],
                    [
                        'name' => 'Recolección de Material',
                        'childrens' => []
                    ],
                    [
                        'name' => 'Inventario',
                        'childrens' => []
                    ]
                ]
            ]


        ];

        return $categories;
    }
}
