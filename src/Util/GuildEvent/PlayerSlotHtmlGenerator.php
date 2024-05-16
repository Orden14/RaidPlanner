<?php

namespace App\Util\GuildEvent;

use App\Checker\SlotAssignmentPermission\SlotAssignmentPermissionChecker;
use App\Entity\Build;
use App\Entity\GuildEventRelation\PlayerSlot;
use App\Entity\User;

final readonly class PlayerSlotHtmlGenerator
{
    public function __construct(
        private SlotAssignmentPermissionChecker $slotAssignmentPermissionChecker,
    ) {}

    public function generateTakenSlotHtml(User $user, PlayerSlot $playerSlot): string
    {
        $userDiv = $this->getUserDivHtml($user, $playerSlot);
        $buildDiv = $this->getBuildDivHtml($playerSlot, true);

        return "$userDiv $buildDiv";
    }

    public function generateFreeSlotHtml(PlayerSlot $playerSlot): string
    {
        return $this->getBuildDivHtml($playerSlot, false);
    }

    private function getUserDivHtml(User $user, PlayerSlot $playerSlot): string
    {
        return "
            <div class='col ge-vertical-center'>
                
                {$this->getFreeSlotButtonHtml($user, $playerSlot)}
                {$this->getTankIconHtml($playerSlot)}
                
                
                <span class='me-3 ge-text '>
                    <img class='small-icon rounded-circle' src='/profile_picture/{$playerSlot->getPlayer()?->getProfilePicture()}' alt='Profile'>
                    {$playerSlot->getPlayer()?->getUsername()}
                </span>
        
        </div>
        ";
    }

    private function getBuildDivHtml(PlayerSlot $playerSlot, bool $slotHasUser): string
    {
        $shouldSlotAttributionButtonShow = !$slotHasUser && $this->slotAssignmentPermissionChecker->checkIfUserCanTakeSlot($playerSlot->getEventBattle());
        $slotAttributionButton = $this->getSlotAttributionIconHtml($playerSlot, $shouldSlotAttributionButtonShow);

        $buildTextSecondaryClass = $slotHasUser ? 'ge-secondary-text' : '';

        /** @var Build $build */
        $build = $playerSlot->getBuild();
        $specialization = $build->getSpecialization();

        return "
            <div class='col-auto ge-md-vertical-center'>
            
                $slotAttributionButton
                                                        
                <span class='pe-md-2'>
                    <a href='/build/{$build->getId()}'
                        title='{$build->getName()}'
                        class='text-decoration-none $buildTextSecondaryClass'
                        style='color: {$specialization?->getJob()?->getColor()}'
                    >
                        <img src='/icon/{$specialization?->getIcon()}' alt='{$specialization?->getName()} icon' title='{$specialization?->getName()}' class='small-icon ge-secondary-img'>
                        {$build->getName()}
                    </a>
                </span>
                
                <span>
                    {$this->getBuildCategoryIconsHtml($build, $slotHasUser)}
                </span>
            </div>
        ";
    }

    private function getSlotAttributionIconHtml(PlayerSlot $playerSlot, bool $shouldButtonShow): string
    {
        $cssHidenClass = $shouldButtonShow ? '' : 'd-none';

        return "
            <span class='custom-link-primary text-decoration-none ge-slot-icon me-2 cursor-pointer {$cssHidenClass}'
                title='Prendre le slot'
                data-controller='guild-event--manage-event-actions'
                data-guild-event--manage-event-actions-url-value='/guild-event/player-slot/battle/{$playerSlot->getEventBattle()?->getId()}/slot/assign/{$playerSlot->getId()}'
                data-slot-assign-event-battle-id='{$playerSlot->getEventBattle()?->getId()}'
            >
                <i class='bi bi-box-arrow-in-right'></i>
            </span>
        ";
    }

    private function getBuildCategoryIconsHtml(Build $build, bool $slotHasUser): string
    {
        $categoryIconsHtml = '';
        $buildImgSecondaryClass = $slotHasUser ? 'ge-secondary-img' : '';

        foreach ($build->getCategories() as $type) {
            $categoryIconsHtml .= "<img src='/icon/{$type->getIcon()}' alt='{$type->getName()} icon' title='{$type->getName()}' class='small-icon $buildImgSecondaryClass'>";
        }

        return $categoryIconsHtml;
    }

    private function getTankIconHtml(PlayerSlot $playerSlot): string
    {
        if ($playerSlot->isTank()) {
            return "<span class='me-1'>
                <img src='/guild_event/tank.png' alt='Tank icon' title='Tank' class='small-icon'>
            </span>";
        }

        return '';
    }

    private function getFreeSlotButtonHtml(User $user, PlayerSlot $playerSlot): string
    {
        return "<span class='link-danger text-decoration-none ge-slot-icon me-2 cursor-pointer' 
                      data-controller='guild-event--confirm-free-slot'
                      data-guild-event--confirm-free-slot-url-value='/guild-event/player-slot/slot/free/{$playerSlot->getId()}'
                      data-current-user='{$user->getUsername()}'
                      data-slot-user='{$playerSlot->getPlayer()?->getUsername()}'
                      data-free-slot-event-battle-id='{$playerSlot->getEventBattle()?->getId()}'
                          >
                    <i class='bi bi-x-lg ps-2' title='Libérer le slot'></i>
                </span>";
    }
}
