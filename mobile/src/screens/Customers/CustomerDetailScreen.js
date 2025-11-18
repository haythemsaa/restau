/**
 * Customer Detail Screen
 */

import React, {useState, useEffect} from 'react';
import {
  View,
  Text,
  ScrollView,
  TouchableOpacity,
  StyleSheet,
  RefreshControl,
  Linking,
} from 'react-native';
import LinearGradient from 'react-native-linear-gradient';
import Icon from 'react-native-vector-icons/Ionicons';
import {customersApi} from '../../services/api';
import {colors, typography, spacing, borderRadius, shadows} from '../../utils/theme';
import Toast from 'react-native-toast-message';

const CustomerDetailScreen = ({route, navigation}) => {
  const {customerId} = route.params;
  const [customer, setCustomer] = useState(null);
  const [loading, setLoading] = useState(true);
  const [refreshing, setRefreshing] = useState(false);

  useEffect(() => {
    loadCustomer();
  }, [customerId]);

  const loadCustomer = async () => {
    try {
      // Mock detailed customer data
      const mockCustomer = {
        id: customerId,
        full_name: 'Jean Dupont',
        email: 'jean.dupont@email.com',
        phone: '+33 6 12 34 56 78',
        tier: 'vip',
        visit_count: 24,
        lifetime_value: '1,250.00',
        days_since_last_visit: 3,
        last_visit_date: '2024-01-15',
        created_at: '2023-06-10',
        preferences: {
          dietary: 'Sans gluten',
          allergies: 'Fruits de mer',
          favorite_dish: 'Filet mignon',
        },
        rfm_score: {
          recency_score: 5,
          frequency_score: 4,
          monetary_score: 4,
          total_score: 13,
        },
        recent_visits: [
          {
            id: '1',
            date: '2024-01-15',
            amount: '85.50',
            items: 3,
          },
          {
            id: '2',
            date: '2024-01-08',
            amount: '120.00',
            items: 5,
          },
          {
            id: '3',
            date: '2023-12-28',
            amount: '95.00',
            items: 4,
          },
        ],
      };

      setCustomer(mockCustomer);
    } catch (error) {
      console.error('Error loading customer:', error);
      Toast.show({
        type: 'error',
        text1: 'Erreur',
        text2: 'Impossible de charger les détails du client',
      });
    } finally {
      setLoading(false);
    }
  };

  const onRefresh = async () => {
    setRefreshing(true);
    await loadCustomer();
    setRefreshing(false);
  };

  const getTierConfig = tier => {
    switch (tier) {
      case 'super_vip':
        return {
          label: '⭐ Super VIP',
          colors: colors.gradient.success,
          icon: 'star',
        };
      case 'vip':
        return {
          label: '👑 VIP',
          colors: colors.gradient.warning,
          icon: 'crown',
        };
      default:
        return {
          label: 'Regular',
          colors: [colors.gray, colors.gray],
          icon: 'person',
        };
    }
  };

  const handleCall = () => {
    if (customer?.phone) {
      Linking.openURL(`tel:${customer.phone}`);
    }
  };

  const handleEmail = () => {
    if (customer?.email) {
      Linking.openURL(`mailto:${customer.email}`);
    }
  };

  const handleSMS = () => {
    if (customer?.phone) {
      Linking.openURL(`sms:${customer.phone}`);
    }
  };

  if (loading || !customer) {
    return (
      <View style={styles.loadingContainer}>
        <Text style={styles.loadingText}>Chargement...</Text>
      </View>
    );
  }

  const tierConfig = getTierConfig(customer.tier);
  const averageSpend = (parseFloat(customer.lifetime_value.replace(',', '')) / customer.visit_count).toFixed(2);

  return (
    <ScrollView
      style={styles.container}
      refreshControl={
        <RefreshControl refreshing={refreshing} onRefresh={onRefresh} />
      }>
      {/* Header with Avatar */}
      <LinearGradient
        colors={tierConfig.colors}
        style={styles.header}
        start={{x: 0, y: 0}}
        end={{x: 1, y: 1}}>
        <TouchableOpacity
          style={styles.backButton}
          onPress={() => navigation.goBack()}>
          <Icon name="arrow-back" size={24} color={colors.white} />
        </TouchableOpacity>

        <View style={styles.avatarContainer}>
          <View style={styles.avatar}>
            <Text style={styles.avatarText}>
              {customer.full_name.split(' ').map(n => n[0]).join('')}
            </Text>
          </View>
        </View>

        <Text style={styles.customerName}>{customer.full_name}</Text>
        <Text style={styles.customerEmail}>{customer.email}</Text>

        <View style={styles.tierBadge}>
          <Text style={styles.tierBadgeText}>{tierConfig.label}</Text>
        </View>
      </LinearGradient>

      {/* Quick Actions */}
      <View style={styles.quickActions}>
        <TouchableOpacity style={styles.actionButton} onPress={handleCall}>
          <LinearGradient
            colors={colors.gradient.primary}
            style={styles.actionButtonGradient}>
            <Icon name="call" size={20} color={colors.white} />
          </LinearGradient>
          <Text style={styles.actionButtonText}>Appeler</Text>
        </TouchableOpacity>

        <TouchableOpacity style={styles.actionButton} onPress={handleEmail}>
          <LinearGradient
            colors={colors.gradient.success}
            style={styles.actionButtonGradient}>
            <Icon name="mail" size={20} color={colors.white} />
          </LinearGradient>
          <Text style={styles.actionButtonText}>Email</Text>
        </TouchableOpacity>

        <TouchableOpacity style={styles.actionButton} onPress={handleSMS}>
          <LinearGradient
            colors={colors.gradient.warning}
            style={styles.actionButtonGradient}>
            <Icon name="chatbubble" size={20} color={colors.white} />
          </LinearGradient>
          <Text style={styles.actionButtonText}>SMS</Text>
        </TouchableOpacity>

        <TouchableOpacity style={styles.actionButton}>
          <LinearGradient
            colors={[colors.secondary, colors.primary]}
            style={styles.actionButtonGradient}>
            <Icon name="create" size={20} color={colors.white} />
          </LinearGradient>
          <Text style={styles.actionButtonText}>Modifier</Text>
        </TouchableOpacity>
      </View>

      {/* Statistics Cards */}
      <View style={styles.statsContainer}>
        <View style={styles.statCard}>
          <Icon name="calendar" size={24} color={colors.primary} />
          <Text style={styles.statValue}>{customer.visit_count}</Text>
          <Text style={styles.statLabel}>Visites</Text>
        </View>

        <View style={styles.statCard}>
          <Icon name="cash" size={24} color={colors.success} />
          <Text style={styles.statValue}>{customer.lifetime_value}€</Text>
          <Text style={styles.statLabel}>LTV</Text>
        </View>

        <View style={styles.statCard}>
          <Icon name="receipt" size={24} color={colors.warning} />
          <Text style={styles.statValue}>{averageSpend}€</Text>
          <Text style={styles.statLabel}>Panier moyen</Text>
        </View>

        <View style={styles.statCard}>
          <Icon name="time" size={24} color={colors.gray} />
          <Text style={styles.statValue}>{customer.days_since_last_visit}j</Text>
          <Text style={styles.statLabel}>Dernière visite</Text>
        </View>
      </View>

      {/* RFM Score */}
      <View style={styles.section}>
        <View style={styles.sectionHeader}>
          <Icon name="analytics" size={20} color={colors.primary} />
          <Text style={styles.sectionTitle}>Score RFM</Text>
        </View>
        <View style={styles.card}>
          <View style={styles.rfmRow}>
            <Text style={styles.rfmLabel}>Récence (R)</Text>
            <View style={styles.rfmBarContainer}>
              <View
                style={[
                  styles.rfmBarFill,
                  {
                    width: `${(customer.rfm_score.recency_score / 5) * 100}%`,
                    backgroundColor: colors.success,
                  },
                ]}
              />
            </View>
            <Text style={styles.rfmScore}>{customer.rfm_score.recency_score}/5</Text>
          </View>

          <View style={styles.rfmRow}>
            <Text style={styles.rfmLabel}>Fréquence (F)</Text>
            <View style={styles.rfmBarContainer}>
              <View
                style={[
                  styles.rfmBarFill,
                  {
                    width: `${(customer.rfm_score.frequency_score / 5) * 100}%`,
                    backgroundColor: colors.primary,
                  },
                ]}
              />
            </View>
            <Text style={styles.rfmScore}>{customer.rfm_score.frequency_score}/5</Text>
          </View>

          <View style={styles.rfmRow}>
            <Text style={styles.rfmLabel}>Montant (M)</Text>
            <View style={styles.rfmBarContainer}>
              <View
                style={[
                  styles.rfmBarFill,
                  {
                    width: `${(customer.rfm_score.monetary_score / 5) * 100}%`,
                    backgroundColor: colors.warning,
                  },
                ]}
              />
            </View>
            <Text style={styles.rfmScore}>{customer.rfm_score.monetary_score}/5</Text>
          </View>

          <View style={styles.rfmTotal}>
            <Text style={styles.rfmTotalLabel}>Score Total</Text>
            <Text style={styles.rfmTotalValue}>
              {customer.rfm_score.total_score}/15
            </Text>
          </View>
        </View>
      </View>

      {/* Preferences */}
      <View style={styles.section}>
        <View style={styles.sectionHeader}>
          <Icon name="heart" size={20} color={colors.danger} />
          <Text style={styles.sectionTitle}>Préférences</Text>
        </View>
        <View style={styles.card}>
          <View style={styles.preferenceRow}>
            <Icon name="restaurant" size={18} color={colors.gray} />
            <Text style={styles.preferenceLabel}>Régime:</Text>
            <Text style={styles.preferenceValue}>
              {customer.preferences.dietary}
            </Text>
          </View>

          <View style={styles.preferenceRow}>
            <Icon name="warning" size={18} color={colors.warning} />
            <Text style={styles.preferenceLabel}>Allergies:</Text>
            <Text style={styles.preferenceValue}>
              {customer.preferences.allergies}
            </Text>
          </View>

          <View style={styles.preferenceRow}>
            <Icon name="star" size={18} color={colors.success} />
            <Text style={styles.preferenceLabel}>Plat favori:</Text>
            <Text style={styles.preferenceValue}>
              {customer.preferences.favorite_dish}
            </Text>
          </View>
        </View>
      </View>

      {/* Recent Visits */}
      <View style={styles.section}>
        <View style={styles.sectionHeader}>
          <Icon name="time" size={20} color={colors.primary} />
          <Text style={styles.sectionTitle}>Visites récentes</Text>
        </View>
        <View style={styles.card}>
          {customer.recent_visits.map((visit, index) => (
            <View
              key={visit.id}
              style={[
                styles.visitRow,
                index < customer.recent_visits.length - 1 && styles.visitRowBorder,
              ]}>
              <View style={styles.visitDate}>
                <Icon name="calendar-outline" size={16} color={colors.gray} />
                <Text style={styles.visitDateText}>{visit.date}</Text>
              </View>
              <View style={styles.visitDetails}>
                <Text style={styles.visitAmount}>{visit.amount}€</Text>
                <Text style={styles.visitItems}>{visit.items} articles</Text>
              </View>
            </View>
          ))}
        </View>
      </View>

      {/* Customer Info */}
      <View style={styles.section}>
        <View style={styles.sectionHeader}>
          <Icon name="information-circle" size={20} color={colors.info} />
          <Text style={styles.sectionTitle}>Informations</Text>
        </View>
        <View style={styles.card}>
          <View style={styles.infoRow}>
            <Text style={styles.infoLabel}>Téléphone</Text>
            <Text style={styles.infoValue}>{customer.phone}</Text>
          </View>

          <View style={styles.infoRow}>
            <Text style={styles.infoLabel}>Dernière visite</Text>
            <Text style={styles.infoValue}>{customer.last_visit_date}</Text>
          </View>

          <View style={styles.infoRow}>
            <Text style={styles.infoLabel}>Client depuis</Text>
            <Text style={styles.infoValue}>{customer.created_at}</Text>
          </View>
        </View>
      </View>

      <View style={styles.bottomPadding} />
    </ScrollView>
  );
};

const styles = StyleSheet.create({
  container: {
    flex: 1,
    backgroundColor: colors.background,
  },
  loadingContainer: {
    flex: 1,
    justifyContent: 'center',
    alignItems: 'center',
    backgroundColor: colors.background,
  },
  loadingText: {
    fontSize: typography.fontSize.base,
    color: colors.textLight,
  },
  header: {
    paddingTop: 60,
    paddingBottom: 40,
    paddingHorizontal: spacing.xl,
    alignItems: 'center',
    borderBottomLeftRadius: 30,
    borderBottomRightRadius: 30,
  },
  backButton: {
    position: 'absolute',
    top: 50,
    left: spacing.base,
    width: 40,
    height: 40,
    borderRadius: 20,
    backgroundColor: 'rgba(255, 255, 255, 0.2)',
    justifyContent: 'center',
    alignItems: 'center',
  },
  avatarContainer: {
    marginBottom: spacing.base,
  },
  avatar: {
    width: 100,
    height: 100,
    borderRadius: 50,
    backgroundColor: 'rgba(255, 255, 255, 0.3)',
    justifyContent: 'center',
    alignItems: 'center',
    borderWidth: 4,
    borderColor: colors.white,
  },
  avatarText: {
    fontSize: typography.fontSize['2xl'],
    fontWeight: 'bold',
    color: colors.white,
  },
  customerName: {
    fontSize: typography.fontSize.xl,
    fontWeight: 'bold',
    color: colors.white,
    marginBottom: 4,
  },
  customerEmail: {
    fontSize: typography.fontSize.sm,
    color: colors.white,
    opacity: 0.9,
    marginBottom: spacing.base,
  },
  tierBadge: {
    paddingHorizontal: spacing.base,
    paddingVertical: spacing.xs,
    borderRadius: borderRadius.full,
    backgroundColor: 'rgba(255, 255, 255, 0.2)',
  },
  tierBadgeText: {
    fontSize: typography.fontSize.sm,
    fontWeight: '600',
    color: colors.white,
  },
  quickActions: {
    flexDirection: 'row',
    justifyContent: 'space-around',
    paddingHorizontal: spacing.base,
    paddingVertical: spacing.xl,
    marginTop: -30,
    backgroundColor: colors.white,
    marginHorizontal: spacing.base,
    borderRadius: borderRadius.lg,
    ...shadows.md,
  },
  actionButton: {
    alignItems: 'center',
    gap: spacing.xs,
  },
  actionButtonGradient: {
    width: 50,
    height: 50,
    borderRadius: 25,
    justifyContent: 'center',
    alignItems: 'center',
  },
  actionButtonText: {
    fontSize: typography.fontSize.xs,
    color: colors.text,
    fontWeight: '500',
  },
  statsContainer: {
    flexDirection: 'row',
    flexWrap: 'wrap',
    paddingHorizontal: spacing.base,
    marginTop: spacing.xl,
    gap: spacing.base,
  },
  statCard: {
    flex: 1,
    minWidth: '45%',
    backgroundColor: colors.white,
    borderRadius: borderRadius.lg,
    padding: spacing.base,
    alignItems: 'center',
    ...shadows.sm,
  },
  statValue: {
    fontSize: typography.fontSize.xl,
    fontWeight: 'bold',
    color: colors.text,
    marginTop: spacing.xs,
  },
  statLabel: {
    fontSize: typography.fontSize.xs,
    color: colors.textLight,
    marginTop: 4,
  },
  section: {
    paddingHorizontal: spacing.base,
    marginTop: spacing.xl,
  },
  sectionHeader: {
    flexDirection: 'row',
    alignItems: 'center',
    marginBottom: spacing.sm,
    gap: spacing.xs,
  },
  sectionTitle: {
    fontSize: typography.fontSize.lg,
    fontWeight: '600',
    color: colors.text,
  },
  card: {
    backgroundColor: colors.white,
    borderRadius: borderRadius.lg,
    padding: spacing.base,
    ...shadows.sm,
  },
  rfmRow: {
    flexDirection: 'row',
    alignItems: 'center',
    marginBottom: spacing.base,
  },
  rfmLabel: {
    width: 100,
    fontSize: typography.fontSize.sm,
    color: colors.text,
    fontWeight: '500',
  },
  rfmBarContainer: {
    flex: 1,
    height: 8,
    backgroundColor: colors.borderLight,
    borderRadius: borderRadius.sm,
    overflow: 'hidden',
    marginHorizontal: spacing.sm,
  },
  rfmBarFill: {
    height: '100%',
    borderRadius: borderRadius.sm,
  },
  rfmScore: {
    width: 40,
    fontSize: typography.fontSize.sm,
    fontWeight: '600',
    color: colors.text,
    textAlign: 'right',
  },
  rfmTotal: {
    flexDirection: 'row',
    justifyContent: 'space-between',
    alignItems: 'center',
    marginTop: spacing.sm,
    paddingTop: spacing.base,
    borderTopWidth: 1,
    borderTopColor: colors.borderLight,
  },
  rfmTotalLabel: {
    fontSize: typography.fontSize.base,
    fontWeight: '600',
    color: colors.text,
  },
  rfmTotalValue: {
    fontSize: typography.fontSize.xl,
    fontWeight: 'bold',
    color: colors.primary,
  },
  preferenceRow: {
    flexDirection: 'row',
    alignItems: 'center',
    marginBottom: spacing.sm,
    gap: spacing.xs,
  },
  preferenceLabel: {
    fontSize: typography.fontSize.sm,
    color: colors.textLight,
    fontWeight: '500',
    marginLeft: spacing.xs,
  },
  preferenceValue: {
    flex: 1,
    fontSize: typography.fontSize.sm,
    color: colors.text,
    fontWeight: '600',
    textAlign: 'right',
  },
  visitRow: {
    flexDirection: 'row',
    justifyContent: 'space-between',
    alignItems: 'center',
    paddingVertical: spacing.sm,
  },
  visitRowBorder: {
    borderBottomWidth: 1,
    borderBottomColor: colors.borderLight,
  },
  visitDate: {
    flexDirection: 'row',
    alignItems: 'center',
    gap: spacing.xs,
  },
  visitDateText: {
    fontSize: typography.fontSize.sm,
    color: colors.textLight,
  },
  visitDetails: {
    alignItems: 'flex-end',
  },
  visitAmount: {
    fontSize: typography.fontSize.base,
    fontWeight: '600',
    color: colors.text,
  },
  visitItems: {
    fontSize: typography.fontSize.xs,
    color: colors.textLight,
    marginTop: 2,
  },
  infoRow: {
    flexDirection: 'row',
    justifyContent: 'space-between',
    alignItems: 'center',
    marginBottom: spacing.sm,
  },
  infoLabel: {
    fontSize: typography.fontSize.sm,
    color: colors.textLight,
    fontWeight: '500',
  },
  infoValue: {
    fontSize: typography.fontSize.sm,
    color: colors.text,
    fontWeight: '600',
  },
  bottomPadding: {
    height: 30,
  },
});

export default CustomerDetailScreen;
